<?php
/*
 *   Copyright 2018- 2021 © Alexander Bombis. All rights reserved.
 *            Developed by Alexander Bombis.
 *            Email: email@alexander-bombis.de
 */

namespace Partarum\PartarumPHP\HTTP\Request {

    use JsonException;
    use Partarum;
    use Partarum\HTTP\Request\Material\Extension;
    use Partarum\HTTP\Request\Routes\Manifest;
    use PHPMailer\PHPMailer\Exception;
    use Predis\Client;

    /**
     * Class Router
     *
     * @package Partarum\HTTP\Request
     */

    class Router {

        /**
         * @var string|mixed
         */
        public string $root;

        /**
         * @var string
         */
        public string $uri;

        /**
         * @var null | string
         */
        public null | string $realURL = NULL;


        /**
         * @var string
         */
        public string $extension;

        public const MODUS_DEV = "dev";

        public const MODUS_PUBLIC = "public";

        /**
         * @var string
         */
        private string $modus = self::MODUS_PUBLIC;

        /**
         * @var array|null
         */
        private ?array $cacheTime;

        private int $type = Partarum::DOCUMENT;

        private Client $redis;

        private array $idInfo;


        /*
         *
         *  Der Switch
         *
         *      - klärt welcher Router gewählt werden soll - also klärt die Route
         *
         *  Der Router
         *
         *      - holt das entsprechende Manifest
         *      - sozusagen ist jedes Manifest ein Router
         *
         *  Der Dispatcher
         *
         *      - geht dann den Weg der Route mit den vorhandenen Informationen
         *      - also goDev() und goPublic()
         */



        /**
         * Router constructor.
         *
         * @param string|null $uri
         */
        public function __construct( Client $redis, array $idInfo, ?string $uri = NULL){

            $this->redis = $redis;

            $this->idInfo = $idInfo;

            $this->root = (!defined("PARTARUM_PLUGIN_PATH")) ? ROOT  : PARTARUM_PLUGIN_PATH ;


            //echo $uri . PHP_EOL;

            if(isset($uri)) {
                $this->setBase($uri);
            }
        }

        private function setBase($uri){

            $this->uri = ($uri === DIRECTORY_SEPARATOR) ? "/landingPage" : $uri;

            $this->realURL = Manifest::getRealUrl($this->uri, $this->type);

            /*
            echo "\n setBase: ";
            echo $this->type . "\n";
            echo "$this->realURL \n";
            */

            if(is_string($this->realURL)) {

                $this->extension = self::getExtension($this->realURL);

                return true;
            }

            return false;
        }

        /**
         * @param string $uri
         *
         * @return Router
         */

        public static function handleThis(string $uri): Router {

            return (new Router($uri));
        }

        /**
         * @param string $url
         *
         * @return string|string[]
         */

        public static function getExtension(string $url){

            return pathinfo($url, PATHINFO_EXTENSION);
        }

        /**
         *
         */
        public function goDev(): void {

            /*
            echo $this->realURL;
            echo "================================ \n================================";
            echo $this->extension;
            */
            Extension::action($this->realURL, $this->extension);
        }

        /**
         * @param array|null $cacheTime
         */
        public function goPublic(?array $cacheTime = NULL): void {
            
            ((isset($cacheTime)) || (isset($this->cacheTime))) && Extension::action($this->realURL, $this->extension, $cacheTime ?? $this->cacheTime);
        }

        public static function getRoutingKey(string $uriPath, $idInfo): string {

            $uri = $uriPath;

            if($uriPath !== "/"){
                $uri = ltrim($uriPath, "/");
            }

            //echo "uri: $uri \n";

            $explodedUri = explode("/", $uri);

            //echo "start with partarum: " . $explodedUri[0] . " \n";

            return ($explodedUri[0] === "Partarum") ? $idInfo["partarumKey"] . ":routes:" . $uri : $idInfo["idKey"] . ":routes:" . $uri;
        }

        /**
         * @param string|null $uri
         *
         * @return bool
         */

        public function with(string $uriPath = "Partarum/error.php") : void {


            // es wird noch nicht zwischen ::user: und ::partarum: unterschieden

            $routingKey = self::getRoutingKey($uriPath, $this->idInfo);

            $routingURL = $this->redis->get($routingKey);

            $this->realURL = ROOT . $this->redis->get($routingKey) ?? "Partarum/error.php";

            /*
            echo "<pre>";
            var_dump($this->idInfo);

            echo "uriPath: $uriPath \n";
            echo "key: $routingKey \n";
            echo "url: $routingURL \n";
            echo "realURL: $this->realURL \n";
            */

            $this->extension = self::getExtension($this->realURL);

            switch ($this->modus) {
                case self::MODUS_PUBLIC:
                    $this->goPublic();
                    break;
                case self::MODUS_DEV :
                    $this->goDev();
                    break;
                default:
                    $this->goPublic();
            }
        }


        public static function addRouter(string | NULL $name, string | array $manifestPath, Client $redis, array &$idInfo): void {

            /*
             *Hier wird der User Router mitgeteilt
             */

            echo "addRouter: $name \n";
            echo "manifestPath:  \n";
            echo "<pre>";
            var_dump($manifestPath);
            var_dump($idInfo);

            $idKey = $idInfo["idKey"];

            $partarumKey = $idInfo["partarumKey"];

            $userManifests = $idInfo["manifests"];

            $manifestArray = (is_array($manifestPath)) ? $manifestPath : [$manifestPath];

            foreach($manifestArray as $key => $value) {

                if(!in_array($value, $userManifests)) {

                    $idInfo["manifests"][] = $value;


                    $json = file_get_contents($value);

                    $config = json_decode($json, true);

                    foreach ($config as $route => $path) {

                        $routingKey = self::getRoutingKey(((is_string($name)) && (strlen($name) > 0)) ? $name . SLASH . $route : $route, $idInfo);

                        echo "routingKey: $routingKey  --- path: $path \n";

                        $redis->set($routingKey, $path);
                    }

                    echo "<pre>";
                    var_dump($idInfo);

                    $redis->set($idKey, json_encode($idInfo));
                }
            }


            // TODO: Das ganze dann noch auf Manifest entsprechend auslagern
            // ! der alte Aufruf -> Manifest::addUserRouter($name, $manifestPath, PARTARUM_PROXY_MANIFEST);
        }

        /**
         * @return string
         */
        public function getModus() : string {

            return $this->modus;
        }

        /**
         * @param string $modus
         */
        public function setModus(string $modus) : void {

            $this->modus = $modus;
        }
        
        public function setType($type) : void {
            
            $this->type = $type;
        }

        /**
         * @return array|null
         */
        public function getCacheTime() : ?array {

            return $this->cacheTime;
        }

        /**
         * @param array|null $cacheTime
         */
        public function setCacheTime(?array $cacheTime = NULL) : void {

            $this->cacheTime = $cacheTime;
        }
    }
}