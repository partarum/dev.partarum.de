<?php
declare(strict_types=1);
/*
 *   Copyright 2018- 2021 © Alexander Bombis. All rights reserved.
 *            Developed by Alexander Bombis.
 *            Email: email@alexander-bombis.de
 */

namespace Partarum\PartarumPHP\HTTP\Request {

    use JsonException;
    use Partarum\Partarum;
    use Partarum\PartarumPHP\HTTP\Request\Material\Extension;
    use Partarum\PartarumPHP\HTTP\Request\Routes\Manifest;
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

        public const DEFAULT_USER_MANIFESTS = [
            "config/assets-manifest.json",
            "config/error-manifest.json",
            "config/fetch-manifest.json",
            "config/routes-manifest.json",
            "config/surface-manifest.json",
            "config/text-manifest.json",
        ];

        public const DEFAULT_PARTARUM_MANIFESTS = [
            "Partarum/PartarumConfig/partarum-manifest.json"
        ];

        /**
         * @var string
         */
        private string $modus = self::MODUS_PUBLIC;

        /**
         * @var array|null
         */
        private ?array $cacheTime = null;

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

        private function setBase(string $uri): bool {

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
         * @param Client $redis
         * @param array $idInfo
         *
         * @return Router
         */

        public static function handleThis(string $uri, Client $redis, array $idInfo): Router {

            return new Router($redis, $idInfo, $uri);
        }

        /**
         * @param string $url
         *
         * @return string
         */

        public static function getExtension(string $url): string {

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

            Extension::action($this->realURL, $this->extension, $cacheTime ?? $this->cacheTime);
        }

        public static function getRoutingKey(string $uriPath, array $idInfo): string {

            $uri = $uriPath;

            if($uriPath !== "/"){
                $uri = ltrim($uriPath, "/");
            }

            $explodedUri = explode("/", $uri);

            return ($explodedUri[0] === "Partarum") ? $idInfo["partarumKey"] . ":routes:" . $uri : $idInfo["idKey"] . ":routes:" . $uri;
        }

        /**
         * @param string $uriPath
         */

        public function with(string $uriPath = "Partarum/error.php") : void {

            $routingKey = self::getRoutingKey($uriPath, $this->idInfo);

            $routingURL = $this->redis->get($routingKey);

            $resolvedPath = (is_string($routingURL) && $routingURL !== "") ? $routingURL : "Partarum/error.php";

            $this->realURL = ROOT . ltrim($resolvedPath, "/");

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


        public static function initRouting(string $id, array $manifestArray, Client $redis, array &$idInfo): void {

            $existingManifests = $idInfo["manifests"] ?? [];

            foreach($manifestArray as $value) {

                $filePath = is_file($value) ? $value : ROOT . ltrim($value, "/");

                if(!is_file($filePath)) {
                    error_log("Router::initRouting manifest not found: " . $value);
                    continue;
                }

                $json = file_get_contents($filePath);

                if($json === false) {
                    continue;
                }

                try {
                    $config = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
                } catch(JsonException $je) {
                    error_log("Router::initRouting JsonException in {$value}: " . $je->getMessage());
                    continue;
                }

                if(is_array($config)) {
                    foreach($config as $route => $path) {
                        $redis->set($id . ":routes:" . $route, $path);
                    }
                }

                if(!in_array($value, $existingManifests, true)) {
                    $idInfo["manifests"][] = $value;
                    $existingManifests[] = $value;
                }
            }

            $redis->set($id . ":initRoutes", true);
        }

        public static function addRouter(string | NULL $name, string | array $manifestPath, Client $redis, array &$idInfo): void {

            /*
             * Hier wird der User Router mitgeteilt
             */

            $idKey = $idInfo["idKey"];

            $userManifests = $idInfo["manifests"] ?? [];

            $manifestArray = (is_array($manifestPath)) ? $manifestPath : [$manifestPath];

            foreach($manifestArray as $value) {

                if(!in_array($value, $userManifests, true)) {

                    $filePath = is_file($value) ? $value : ROOT . ltrim($value, "/");

                    if(!is_file($filePath)) {
                        error_log("Router::addRouter manifest not found: " . $value);
                        continue;
                    }

                    $json = file_get_contents($filePath);

                    if($json === false) {
                        continue;
                    }

                    try {
                        $config = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
                    } catch(JsonException $je) {
                        error_log("Router::addRouter JsonException in {$value}: " . $je->getMessage());
                        continue;
                    }

                    $idInfo["manifests"][] = $value;
                    $userManifests[] = $value;

                    if(is_array($config)) {
                        foreach ($config as $route => $path) {

                            $routingKey = self::getRoutingKey(((is_string($name)) && (strlen($name) > 0)) ? $name . SLASH . $route : (string)$route, $idInfo);

                            $redis->set($routingKey, $path);
                        }
                    }

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
        
        public function setType(int $type) : void {
            
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