<?php
namespace PartarumCLI\v_a\TCP {

    require_once  "../../../vendor/autoload.php";
    require_once "../../System/Root.php";

    use JsonException;
    use PartarumCLI\v_a\System\Root;
    use RuntimeException;
    use Workerman\Worker;

    class Server {
        
        public ?Worker $worker = NULL;
        
        private array $context = [];

        private ?WebSocketObject $config;
        
        public function __construct($config){

            Root::setRootObject();

            foreach($config->context as $key => $value){

                $this->context[$key] = $value;
            }

            unset($config->context);

            $this->config = $config;

            print_r($this->context);

            $this->worker = new Worker("websocket://" . $this->config->address, $this->context);

            $this->checkContext();

            $this->checkConfig();


            $this->worker->onConnect = static function($connection){

                echo "New connection\n";
             };


            $this->worker->onMessage = function($connection, $data){

                /*
                 *  Hier jetzt $data zur Route auswerten
                 *
                 *  $data muss ein JSON - String sein !!!
                 *
                 */

                echo "ONMESSAGE aktiv";

                try {

                    $j = json_decode($data, FALSE, 512, JSON_THROW_ON_ERROR);

                    print_r($j);

                    if(isset($j->route)){

                        if(isset($this->config->routes->{$j->route})){

                            $route = $this->config->routes->{$j->route};

                            $connection->send(json_encode($route, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES));

                            if(isset($route->path) && file_exists(Root::$DOCUMENT_ROOT . DIRECTORY_SEPARATOR . $route->path)) {

                                try {

                                    $connection->send($route->path);

                                    $classFilePath = Root::$DOCUMENT_ROOT . $route->path;

                                    $connection->send($classFilePath);

                                    require $classFilePath;

                                    unset($route->path);

                                    foreach($route as $routeKey => $routeValue){

                                        $connection->send($routeKey);

                                        switch($routeKey) {

                                            case "class":

                                                $className = str_replace('\\\\','\\', $routeValue);

                                                $connection->send($className);

                                                if(class_exists("\\" . $className)) {

                                                    //$class = get_class($routeValue); // Ab PHP8.0 $routeValue::class !!!

                                                    $connection->send($className . "exists");

                                                    $object = new $className();

                                                    print_r($object);

                                                    $object->onMessage($connection, $data);
                                                } else {

                                                    $connection->send($className . " not exists");
                                                }

                                                break;
                                        }
                                    }

                                } catch(RuntimeException $e){

                                    print_r($e);
                                }
                            } else {

                                $connection->send("Fehler - 105");
                            }

                        } else {

                            echo "Hat nicht geklappt" . PHP_EOL;
                            print_r($this->config);
                        }

                        $connection->send(json_encode($this->config, JSON_THROW_ON_ERROR));
                    } else {

                        $connection->send("pups");
                    }

                    $connection->send("hello DU DA ");

                } catch(JsonException $e){

                    $connection->send("Non valid Data send.");
                }


            };


            $this->worker->onClose = static function($connection){
                echo "Connection closed\n";
            };

          
        }

        private function checkContext(){

            (isset($this->context["ssl"])) && $this->setSSL();
        }

        private function checkConfig(){

            (isset($this->config->worker)) && $this->setCountOfWorker();
        }

        private function setSSL(){

            $this->worker->transport = 'ssl';
        }

        private function setCountOfWorker(){

            $this->worker->count = $this->config->worker ?? 1;
        }

        public function start(){
            
            Worker::runALL();
        }
    }
}