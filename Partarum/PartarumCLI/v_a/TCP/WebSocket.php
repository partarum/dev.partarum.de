<?php 
namespace PartarumCLI\v_a\TCP {

    require "Server.php";
    require "Listener.php";
    require "WebSocketObject.php";

    use JsonException;
    use PartarumCLI\TCP\Listener;
    use PartarumCLI\v_a\System\Root;
    use RuntimeException;

    class WebSocket {
    
        public ?Server $server = NULL;
        
        public ?Listener $listener = NULL;

        public ?WebSocketObject $config = NULL;

        public ?int $port;

        public ?object $manifest;

        private array $contextKeys = ["ssl"];

        private array $configKeys = ["address", "worker"];

        private bool $verify = FALSE;

        public function __construct() {

            $this->config = new WebSocketObject();
        }

        public function createServer($port){

            $this->port = $port;

            $this->getManifest();

            $this->verify = $this->checkManifest();

        }
        
        public function start(){

            if($this->verify === TRUE) {

                $this->server = new Server($this->config);

                $this->server->start();
            }
        }
        
        public function getManifest(){

            try {

                $manifestFile = file_get_contents(Root::$DOCUMENT_ROOT . "Partarum/PartarumCLI/config/websocket-manifest.json");

                try {

                    $this->manifest = json_decode($manifestFile, FALSE, 512, JSON_THROW_ON_ERROR);

                } catch(JsonException $e){

                }

            } catch(RuntimeException $e) {

            }
        }

        private function checkManifest() : bool {

            if(isset($this->manifest->{$this->port})){

                foreach($this->manifest->{$this->port} as $key => $value){

                    switch ($key) {

                        case "config":

                            foreach($value as $param => $paramValue){

                                if(in_array($param, $this->configKeys, TRUE)){

                                    $this->config->{$param} = $paramValue;

                                } elseif (in_array($param, $this->contextKeys, TRUE)){

                                    $array = $paramValue;

                                    $this->config->context->{$param} = (array) $array;

                                }
                            }

                            break;
                        case "route":
                        case "routes":

                            foreach($value as $route => $filepath){

                                $this->config->routes->{$route} = $filepath;
                            }
                            break;
                    }

                }

                return TRUE;
            }

            return FALSE;

        }
        
        public function createListener(){
            
            $this->listener = new Listener();
        }
    }
}