<?php

require_once "vendor/autoload.php" ?? $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

require_once "Partarum/PartarumPHP/System/Autoloader.php" ?? $_SERVER["DOCUMENT_ROOT"] . "/Partarum/PartarumPHP/System/Autoloader.php";

use Partarum\System\Autoloader;

spl_autoload_register([Autoloader::class, "autoload"]);

use Partarum\Security\Token\UUID;
use Partarum\HTTP\Request\Switcher;
use Partarum\HTTP\Request\Router;
use Partarum\System\Root;

use Predis\Client;
use Dotenv\Dotenv;
use Medoo\Medoo;
use Predis\Response\Status;

/**
 * Class Partarum
 */

class Partarum {


    /**
     *
     */
    public const DOCUMENT = 2;

    /**
     *
     */
    public const PROXY = 4;

    /**
     *
     */
    public const PLUGIN = 8;

    public const REDIS_KEY_BASE_USER = ":user:";

    public const REDIS_KEY_BASE_PARTARUM = ":partarum:";

    /**
     * @var bool
     */
    public static bool $inUse = false;

    /**
     * @var int
     */
    public int $type = 2;

    /**
     * @var Router
     */
    public Router $router;

    public static Switcher $switcher;

    public static array $proxyRoutes = [];

    public static null | array $proxyInUse = NULL;

    private array $partarumENV;

    //private null | ExtendedCacheItemPoolInterface $redisCache = NULL;

    private Client $redis;

    public string $topic;

    public string $id;

    private null | string $getInfo = NULL;

    public string $idKey;

    public mixed $idInfo;

    public string $partarumKey;
    public mixed $partarumInfo;


    /**
     * @param string $name
     * @param int $type
     * @param string|array|null $option
     */
    public function __construct(string $name, int $type = Partarum::DOCUMENT, null | string | array $option = NULL) {

        $this->topic = $name;

        $this->type = $type;

        $this->loadENV();

        $this->defineConstants($type, $option);

        $this->activateRedis();

        if($this->redis->exists(ROOT)) {

            $this->id = $this->redis->get(ROOT);

        } else {

            $this->id = UUID::v4()->current();

            $this->redis->set(ROOT, $this->id);
        }

        $this->partarumKey = $this->id . Partarum::REDIS_KEY_BASE_PARTARUM;

        $this->idKey = match($name){
            str_starts_with("Partarum", $name) => $this->partarumKey,
            default => $this->id . Partarum::REDIS_KEY_BASE_USER
        };

        $idInfoArray = [
            "start" => date("Y-m-d H:i:s"),
            "id" => $this->id,
            "idKey" => $this->idKey,
            "partarumKey" => $this->partarumKey,
            "root" => ROOT,
            "type" => (str_starts_with("Partarum", $name)) ? "partarum" : "user",
            "manifests" => []
        ];

        // ! json_decode(NULL) === deprecated
        $this->idInfo = json_decode($this->redis->get($this->idKey) ?? json_encode($idInfoArray), true) ?? $idInfoArray;

        /* Prüfen und ggf. initialisieren des Routings im DOCUMENT_ROOT - damit ist noch nicht $name bezogene Routings gemeint
         *
         *  config/*-manifest.json
         *  Partarum - Manifest sind globale Pfade für den ganzen DOCUMENT:ROOT
         */
        if(!$this->redis->exists($this->idKey . ":initRoutes")) {

            // User - Routing
            $this->initRouting($this->idKey, [
                "config/assets-manifest.json",
                "config/error-manifest.json",
                "config/fetch-manifest.json",
                "config/routes-manifest.json",
                "config/surface-manifest.json",
                "config/text-manifest.json",
            ]);
        }

        if(!$this->redis->exists($this->partarumKey . ":initRoutes")) {

            // Partarum - Routing
            $this->initRouting($this->partarumKey, [
                "Partarum/PartarumConfig/partarum-manifest.json"
            ]);
        }

        $this->redis->set($this->idKey, json_encode($this->idInfo));

        self::$inUse = true;
    }

    /*
     * Grundliegende Sachen aktivieren Constanten, Redis etc...
     */
    private function init() {

    }

    /*
     * .env laden
     */
    private function loadENV(): void {

        /*
         * Laden der .env Dateien aus PartarumLorem automatisieren
         *
         * ! In die Haupt-ENV sollten keine sensiblen Daten, da diese global in $_ENV, $_SERVER zu finden sind.
         *
         * ! Sensible Daten gehören in eine oder mehrfache .env Datei/en, welche dann speziell im Einsatz in ein eigenes Array geladen werden
         *
         * + .ghost (folder)
         *       + .partarum (folder)
         *       + .user (folder)
         *          + .env (folder)
         *          + .env.dev (file)
         *          + .env.test (file)
         *          + .env.prod (file)
         */

        $dotenv = Dotenv::createImmutable(".ghost/.user/.envBox");

        $dotenv->load();

        $dotenv = Dotenv::createArrayBacked("Partarum/PartarumIntern/Security");

        $this->partarumENV = $dotenv->load();
    }

    public function loadSecret( string $path) : array {

        $loremPath = ROOT . SLASH . ".ghost/.user/.envBox";

        $dotEnv = Dotenv::createArrayBacked($loremPath . SLASH . $path);

        return $dotEnv->load();
    }

    public function createDBConnection( null | array $option = null) : Medoo {

        $env = $this->loadSecret($_ENV["DB"]);

        $dbOptions = [
            "type" => $env["DB_TYPE"],
            "host" => $env["DB_HOST"],
            "database" => $env["DB_NAME"],
            "username" => $env["DB_USER"],
            "password" => $env["DB_PW"]
        ];

        return new Medoo($option ?? $dbOptions);
    }

    /*
     * Redis - Verbindung herstellen
     */
    private function activateRedis(): void {

        try {
            $this->redis = new Client([
                "host" => $this->partarumENV["REDIS_IP"],
                "port" => $this->partarumENV["REDIS_PORT"],
                "database" => $this->partarumENV["REDIS_DB"],
                "username" => $this->partarumENV["REDIS_USER"],
                "password" => $this->partarumENV["REDIS_PASS"]
            ]);
        } catch(Exception $ex) {

            echo "<pre>";
            print_r($ex);
        }
    }

    public function setToCache(string $key, string | int $value, int $expires = 0) : Status {

        /*
         *  Es empfiehlt sich bei Bearer-Token immer ein Expires zu setzen, damit der Cache nicht so überfüllt wird
         */

        return match($expires){
            0 => $this->redis->set($this->idKey . ":cache:" . $key, $value),
            default => $this->redis->set($this->idKey . ":cache:" . $key, $value, "EX", $expires)
        };
    }

    public function getFromCache(string $key) : null | string {

        return $this->redis->get($this->idKey . ":cache:" . $key);
    }

    public function isIntoCache(string $key) : bool {

        return $this->redis->exists($this->idKey . ":cache:" . $key);
    }

    /*
     * Routing initialisieren
     *      + Partarum
     *      + User
     */
    private function initRouting($id, $manifestArray): void {

        // TODO: Auslagern nach  Router

        foreach($manifestArray as $key => $value) {

            $json = file_get_contents($value);

            $config = json_decode($json, true);

            foreach($config as $route => $path){

                $this->redis->set($id . ":routes:" . $route, $path);
            }

            /*
            echo "<pre>";
            var_dump($this->idInfo);
            */

            $this->idInfo["manifests"][] = $value;
        }

        $this->redis->set($id . ":initRoutes", true);
    }

    /*
     * Konstanten definieren
     */
    private function defineConstants($type, $option): void {

        $manifestArray = [];

        $rootPath = "";

        if($type !== self::DOCUMENT){

            if(isset($option["manifest"])){

                // vorwiegend self::PLUGIN

                try {
                    $manifestArray = json_decode($option["manifest"], TRUE, 512, JSON_THROW_ON_ERROR);

                    $rootPath = $manifestArray["rootPath"] || $manifestArray["proxyPath"];

                } catch(JsonException $je){

                    echo "<pre>";
                    print_r($je);
                }
            } else {

                // vorwiegend self::PROXY

                $rootPath = $option["rootPath"] ?? $option["proxyPath"];
            }

        }


        if((self::$inUse === false) && ($type === self::PLUGIN || self::DOCUMENT)) {

            $getRoot = Root::getPath();

            $root = ($getRoot[-1] === "/") ? $getRoot : $getRoot . "/";

            (!defined("ROOT")) && define("ROOT", ("" === $rootPath) ? $root : $root . $rootPath);

            (!defined("SLASH")) && define("SLASH", DIRECTORY_SEPARATOR);

            (!defined("PARTARUM_PATH")) && define("PARTARUM_PATH", __DIR__);
        }

        switch($type){

            case self::PROXY:

                //define("PARTARUM_PROXY_PATH", $option["proxyPath"]);

                define("PARTARUM_PROXY_PATH", $option["proxyPath"] ?? $rootPath);

                break;

            case self::PLUGIN:

                define("PARTARUM_PLUGIN_PATH", ROOT . SLASH . $manifestArray["pluginPath"]);

                define("PARTARUM_RELATIV_PLUGIN_PATH", SLASH . $manifestArray["rootPath"] . SLASH . $manifestArray["pluginPath"]);
        }
    }

    /**
     *
     */
    public function addAutoloader($content, $matter): void {

        Autoloader::addUserAutoloader($content, $matter);
        // Wichtig !!!

    }

    public function addRouter(string $name, string | array $manifestPath): void {

        Router::addRouter($name, $manifestPath, $this->redis, $this->idInfo);
    }


    /**
     * @return Router
     */
    public function goDev() : Router {

        $this->router = new Router($this->redis, $this->idInfo);
        $this->router->setType($this->type);
        $this->router->setModus(Router::MODUS_DEV);

        return $this->router;
    }

    /**
     * @return Router
     */
    public function goPublic($cacheTime) : Router {

        $this->router = new Router($this->redis);
        $this->router->setModus(Router::MODUS_PUBLIC);
        $this->router->setCacheTime($cacheTime);

        return $this->router;
    }
}