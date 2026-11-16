<?php
declare(strict_types=1);

namespace Partarum;

require_once dirname(__DIR__) . "/vendor/autoload.php";

use Partarum\PartarumPHP\Cache\RedisCache;
use Partarum\PartarumPHP\Security\Token\UUID;
use Partarum\PartarumPHP\HTTP\Request\Switcher;
use Partarum\PartarumPHP\HTTP\Request\Router;
use Partarum\PartarumPHP\System\EnvLoader;
use Partarum\PartarumPHP\System\Root;
use Partarum\PartarumPHP\System\SystemAccess;

use JsonException;
use Predis\Client;
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
    public function __construct(string $name = "Partarum", int $type = Partarum::DOCUMENT, null | string | array $option = NULL) {

        $this->topic = $name;

        $this->type = $type;

        $this->defineConstants($type, $option);

        $this->loadENV();

        $this->activateRedis();

        if($this->redis->exists(ROOT)) {

            $this->id = $this->redis->get(ROOT);

        } else {

            $this->id = UUID::v4()->current();

            $this->redis->set(ROOT, $this->id);
        }

        $this->partarumKey = $this->id . Partarum::REDIS_KEY_BASE_PARTARUM;

        $this->idKey = str_starts_with($name, "Partarum")
            ? $this->partarumKey
            : $this->id . Partarum::REDIS_KEY_BASE_USER;

        $idInfoArray = [
            "start" => date("Y-m-d H:i:s"),
            "id" => $this->id,
            "idKey" => $this->idKey,
            "partarumKey" => $this->partarumKey,
            "root" => ROOT,
            "type" => str_starts_with($name, "Partarum") ? "partarum" : "user",
            "manifests" => []
        ];

        $this->idInfo = json_decode($this->redis->get($this->idKey) ?? json_encode($idInfoArray), true) ?? $idInfoArray;

        if(!$this->redis->exists($this->idKey . ":initRoutes")) {
            $this->initRouting($this->idKey, Router::DEFAULT_USER_MANIFESTS);
        }

        if(!$this->redis->exists($this->partarumKey . ":initRoutes")) {
            $this->initRouting($this->partarumKey, Router::DEFAULT_PARTARUM_MANIFESTS);
        }

        $this->initSystemAccess();

        $this->redis->set($this->idKey, json_encode($this->idInfo));

        self::$inUse = true;
    }

    /**
     * Wechselt eine Verzeichnisebene nach oben, solange Zugriffsrechte / open_basedir dies erlauben.
     *
     * @return bool
     */
    private function goDown(): bool {

        return SystemAccess::goDown();
    }

    /**
     * Überprüft einmalig beim Systemstart, wie weit der PHP-User im Dateisystem nach oben sieht,
     * auf welche Ordner er Zugriff hat und in welchem User-Kontext der Prozess läuft,
     * und speichert das Ergebnis in Redis.
     */
    private function initSystemAccess(): void {

        SystemAccess::init($this->redis, $this->partarumKey, __FILE__);
    }

    /*
     * .env laden
     */
    private function loadENV(): void {

        $this->partarumENV = EnvLoader::load(ROOT);
    }

    public function loadSecret( string $path) : array {

        return EnvLoader::loadSecret($path, ROOT);
    }

    public function createDBConnection( null | array $option = null) : Medoo {

        if($option !== null) {
            return new Medoo($option);
        }

        $env = $this->loadSecret($_ENV["DB"]);

        $dbOptions = [
            "type" => $env["DB_TYPE"],
            "host" => $env["DB_HOST"],
            "database" => $env["DB_NAME"],
            "username" => $env["DB_USER"],
            "password" => $env["DB_PW"]
        ];

        return new Medoo($dbOptions);
    }

    /*
     * Redis - Verbindung herstellen
     */
    private function activateRedis(): void {

        $this->redis = RedisCache::connect($this->partarumENV);
    }

    public function setToCache(string $key, string | int $value, int $expires = 0) : Status {

        return RedisCache::set($this->redis, $this->idKey, $key, $value, $expires);
    }

    public function getFromCache(string $key) : null | string {

        return RedisCache::get($this->redis, $this->idKey, $key);
    }

    public function isIntoCache(string $key) : bool {

        return RedisCache::has($this->redis, $this->idKey, $key);
    }

    /*
     * Routing initialisieren
     *      + Partarum
     *      + User
     */
    private function initRouting(string $id, array $manifestArray): void {

        Router::initRouting($id, $manifestArray, $this->redis, $this->idInfo);
    }

    /*
     * Konstanten definieren
     */
    private function defineConstants(int $type, null | string | array $option): void {

        $manifestArray = [];

        $rootPath = "";

        if($type !== self::DOCUMENT){

            if(is_array($option) && isset($option["manifest"])){

                // vorwiegend self::PLUGIN

                try {
                    $manifestArray = json_decode($option["manifest"], TRUE, 512, JSON_THROW_ON_ERROR);

                    $rootPath = $manifestArray["rootPath"] ?? $manifestArray["proxyPath"] ?? "";

                } catch(JsonException $je){

                    error_log("Partarum::defineConstants JsonException: " . $je->getMessage());
                }
            } elseif(is_array($option)) {

                // vorwiegend self::PROXY

                $rootPath = $option["rootPath"] ?? $option["proxyPath"] ?? "";
            }

        }


        if((self::$inUse === false) && ($type === self::PLUGIN || $type === self::DOCUMENT)) {

            $getRoot = Root::getPath();

            $root = ($getRoot[-1] === "/") ? $getRoot : $getRoot . "/";

            (!defined("ROOT")) && define("ROOT", ("" === $rootPath) ? $root : $root . $rootPath);

            (!defined("SLASH")) && define("SLASH", DIRECTORY_SEPARATOR);

            (!defined("PARTARUM_PATH")) && define("PARTARUM_PATH", __DIR__);
        }

        switch($type){

            case self::PROXY:

                (!defined("PARTARUM_PROXY_PATH")) && define("PARTARUM_PROXY_PATH", $option["proxyPath"] ?? $rootPath);

                break;

            case self::PLUGIN:

                (!defined("PARTARUM_PLUGIN_PATH")) && define("PARTARUM_PLUGIN_PATH", ROOT . SLASH . ($manifestArray["pluginPath"] ?? ""));

                (!defined("PARTARUM_RELATIV_PLUGIN_PATH")) && define("PARTARUM_RELATIV_PLUGIN_PATH", SLASH . ($manifestArray["rootPath"] ?? "") . SLASH . ($manifestArray["pluginPath"] ?? ""));
        }
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
     * @param array|null $cacheTime
     * @return Router
     */
    public function goPublic(?array $cacheTime = null) : Router {

        $this->router = new Router($this->redis, $this->idInfo);
        $this->router->setType($this->type);
        $this->router->setModus(Router::MODUS_PUBLIC);
        $this->router->setCacheTime($cacheTime);

        return $this->router;
    }
}