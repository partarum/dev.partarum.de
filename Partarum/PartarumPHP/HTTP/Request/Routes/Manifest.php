<?php
/*
 *   Copyright 2018- 2021 © Alexander Bombis. All rights reserved.
 *            Developed by Alexander Bombis.
 *            Email: email@alexander-bombis.de
 */
namespace Partarum\PartarumPHP\HTTP\Request\Routes {

    use Partarum\Partarum;
    use Partarum\PartarumPHP\HTTP\Request\Routes\ManifestPartarum;
    use Partarum\PartarumPHP\HTTP\Request\Routes\ManifestProxy;
    use Partarum\PartarumPHP\HTTP\Request\Routes\ManifestRoots;
    use stdClass;

    class Manifest {

        public string $root;

        public array $uriArray;

        public string $manifest;

        protected int $type;

        public static null | object $userManifest;

        public static null | array $partarum_proxy_manifest;

        public function __construct(){

            $this->root = (!defined("PARTARUM_PLUGIN_PATH")) ? ROOT : PARTARUM_PLUGIN_PATH ;
        }

        protected function getManifest(string $uri, $type): array {

            /*
             *  Was habe ich?
             *
             *  ggf. den Typ:
             *          Document    -  alles was vom DocumentRoot aus läuft
             *                          - ManifestPartarum
             *                          - ManifestRoots
             *
             *          Proxy       -   eigen gesetztes "DocumentRoot"
             *                          - ManifestProxy
             *
             *
             *          Plugin      -   komplett neue PartarumOberfläche / DocumentRoot
             *                          - ManifestPlugin
             *
             *  V
             *
             */

            $needleURI = $uri;

            $getManifestPath = NULL;

            //var_dump($uri);


            echo "Manifest:getManifest: \n";
            echo "uri: " . $uri . "\n";
            echo "type: " . $type . "\n";

            switch($type){

                case Partarum::PROXY:

                    echo "PartarumProxy \n";

                    Partarum::$proxyRoutes[] = $uri;

                    break;

                case Partarum::PLUGIN:

                    if((defined("PARTARUM_PLUGIN_PATH")) && (str_starts_with($uri, PARTARUM_RELATIV_PLUGIN_PATH))){

                        $needleURI = substr($uri, strlen(PARTARUM_RELATIV_PLUGIN_PATH) + 1);
                    }

            }

            //print_r($uri);

            $this->uriArray = explode("/", $needleURI);

            echo "this uriArray: " . count($this->uriArray) . "\n";
            echo "<pre>";
            print_r($this->uriArray);


            if((count($this->uriArray) > 2) && ($this->uriArray[1] === $this->uriArray[2])){
                array_splice($this->uriArray, 1, 1);
            }


            if((count($this->uriArray) > 1) && (array_filter($this->uriArray, static fn($val) => (($val === "Partarum") || ($val === "PartarumJS"))))){

                echo "test if Partarum \n";

                /*
                 *  Partarum::PROXY fehlt hier, da der Path für den Workspace in der partarum-manifest.json ist
                 *
                 *  Welche Möglichkeiten habe ich um auf das Routing zu kommen, ohne weiter auf den Workspace hier einzugehen?
                 *
                 *  1. Der Type - Partarum::PROXY
                 *  2. Das PARTARUM_PROXY_MANIFEST - ist allerdings im normalen Routing nicht angegeben
                 *  3. PARTARUM_PROXY_IN_USE - alle bis jetzt registrierten baseRoutes + Inhalt von PARTARUM_PROXY_MANIFEST - ist allerdings auch nur gültig, wenn gerade registriert wird
                 *  4. Partarum::$proxyInUse - alle bis jetzt regustrierten baseRoutes wie in PARTARUM_PROXY_IN_USE - überall verwendbar
                 *
                 *  Problem:
                 * ! Wenn ein zusätzlicher Routingordner hinter PARTARUM_PROXY_MANIFEST["baseRoute"] erscheint - wird es als ganz normales Partarum - Routing betrachtet
                 */

                if(defined('PARTARUM_PROXY_PATH')){
                echo "partarum_proxy_path: " . PARTARUM_PROXY_PATH . "\n";
                }

                echo "partarum:proxyInUse - array_keys: \n";
                echo "<pre>";
                var_dump(array_keys(Partarum::$proxyInUse));


                $getManifestPath = "";

                if(defined('PARTARUM_PROXY_MANIFEST')){

                    echo "<pre>";
                    var_dump(array_intersect_assoc($this->uriArray, PARTARUM_PROXY_MANIFEST["uriArray"]));

                    $getManifestPath = $this->manifestProxy();

                } /*else {

                    if($type === Partarum::PROXY){

                    } else {

                    }
                }*/



                $getManifestPath = ($type === Partarum::PROXY) ? $this->manifestProxy() : $this->manifestPartarum();

                /*
                echo "<pre>";
                print_r($this->manifest);
                */

            } else {

                if(isset(self::$userManifest, self::$userManifest->{$this->uriArray[0]})){

                    $getManifestPath = self::$userManifest->{$this->uriArray[0]};

                } else {

                    if($type === Partarum::PROXY){

                        echo "Prox!";

                        $getManifestPath = $this->manifestProxy();

                    } else {

                        $getManifestPath = $this->manifestRoots();
                    }
                }
            }




            if((str_starts_with($getManifestPath, DIRECTORY_SEPARATOR)) && (!str_ends_with($this->root, DIRECTORY_SEPARATOR))){

                $this->manifest = $getManifestPath;

            } else {

                if(str_ends_with($this->root, DIRECTORY_SEPARATOR)){

                    $this->manifest = $getManifestPath;

                } else {

                    $this->manifest = DIRECTORY_SEPARATOR . $getManifestPath;
                }
            }

            if(!isset($this->manifest)){

                $this->manifest = ManifestRoots::getPath($this->uriArray);

                echo PHP_EOL . "Route = " . $this->manifest . PHP_EOL;

                echo "Route nicht vorhanden";

                $this->uriArray = ["", "404"];
            }

            return [
                "manifestValue" => json_decode(file_get_contents($this->root . $this->manifest), TRUE, 512, JSON_THROW_ON_ERROR),
                "uriArray" => $this->uriArray,
                "root" => $this->root
            ];
        }

        protected function manifestRoots(): string {

            //echo "<pre>";
            //print_r($this->uriArray);

            ($this->uriArray[0] !== "") && array_unshift($this->uriArray, "");

            //echo "<pre>";
            //print_r($this->uriArray);

            return ManifestRoots::getPath($this->uriArray);
        }


        protected function manifestPartarum() : string {

            ($this->uriArray[0] !== "") && array_unshift($this->uriArray, "");

            return ManifestPartarum::getPath($this->uriArray);
        }

        protected function manifestProxy() : string {

            ($this->uriArray[0] !== "") && array_unshift($this->uriArray, "");

            return ManifestProxy::getPath($this->uriArray, self::$userManifest);
        }

        protected function manifestPlugin(){

        }


        public static function getRealUrl(string $uri, int $type = Partarum::DOCUMENT): null | string {

            echo "uri: " . $uri . "\n";

            echo "type: " . $type . "\n";

            $manifest =  (new Manifest)->getManifest($uri, $type);

            echo "Manifest:getRealUrl: \n";
            echo "<pre>";
            print_r($manifest);

            $urlFromManifest =  (count($manifest["uriArray"]) > 1) ? implode("/", array_slice($manifest["uriArray"], 0)) : $manifest["uriArray"][0]; //offset 1

            $path = (count($_GET) > 0) ? substr($urlFromManifest, 0, strpos($urlFromManifest, '?')) : $urlFromManifest;

            if($path[0] === "/"){
                $path = trim($path, "/");
            }

            // ! Hier dem User zwingend die Auswahl für die Errorausgabe lassen

            /*
                !      bei Ausgabe des Statuses 404 - eine Log anlegen und dem User die Möglichkeit geben jene zu lesen !!!! z.B. Timestring.log
                !      - was dann in Partarum.debug() aufgerufen werden kann
             */

            $realUrl = $manifest["manifestValue"][$path] ?? "/Partarum/error.php"; //?? file_get_contents("https://partarum.download/src/Partarum/public/html/error_wrongWay.html");

            return str_replace("//","/", $manifest["root"] . $realUrl);
        }

        protected static function setUserObject() : void {

            self::$userManifest = new stdClass();
        }

        public static function addUserRouter( string $name, string $path, array $proxyManifest) : void {

            (!isset(self::$userManifest)) && self::setUserObject();

            self::$userManifest->{$name} = $path;

            echo "Manifest:addUserRouter: \n";
            echo "<pre>";
            print_r(self::$userManifest);
            print_r($proxyManifest);
        }

    }
}

