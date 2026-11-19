<?php
declare(strict_types=1);

require_once dirname(__DIR__) . "/vendor/autoload.php";

use Partarum\Partarum;
use Partarum\PartarumIntern\Security\LoremLicentiam;
use Partarum\PartarumPHP\Cache\RedisCache;
use Partarum\PartarumPHP\Cache\SharedMemory;
use Partarum\PartarumPHP\Cache\WorkerMap;
use Partarum\PartarumPHP\Config\GlobalConfig;
use Partarum\PartarumPHP\Database\CacheConnect;
use Partarum\PartarumPHP\Database\DatabaseData;
use Partarum\PartarumPHP\Database\DatabaseDataObject;
use Partarum\PartarumPHP\Database\DatabaseMysqli;
use Partarum\PartarumPHP\Database\DatabasePDO;
use Partarum\PartarumPHP\Document\HTMLArticle;
use Partarum\PartarumPHP\Document\HTMLAttribute;
use Partarum\PartarumPHP\Document\HTMLCollection;
use Partarum\PartarumPHP\Document\HTMLDocument;
use Partarum\PartarumPHP\Document\HTMLDom;
use Partarum\PartarumPHP\Document\HTMLElement;
use Partarum\PartarumPHP\Document\HTMLetcElementInfo;
use Partarum\PartarumPHP\Document\HTMLFamily;
use Partarum\PartarumPHP\Document\HTMLfooter;
use Partarum\PartarumPHP\Document\HTMLgetEtc;
use Partarum\PartarumPHP\Document\HTMLhead;
use Partarum\PartarumPHP\Document\HTMLheader;
use Partarum\PartarumPHP\Document\HTMLmain;
use Partarum\PartarumPHP\Document\HTMLObject;
use Partarum\PartarumPHP\Document\HTMLPage;
use Partarum\PartarumPHP\Document\HTMLPlain;
use Partarum\PartarumPHP\Document\HTMLScript;
use Partarum\PartarumPHP\Document\HTMLSeries;
use Partarum\PartarumPHP\Document\HTMLService;
use Partarum\PartarumPHP\Document\HTMLText;
use Partarum\PartarumPHP\Document\NodeCollection;
use Partarum\PartarumPHP\Document\NodeObject;
use Partarum\PartarumPHP\Filesystem\DirectoryBridgeGenerator;
use Partarum\PartarumPHP\Filesystem\DirectoryGenerator;
use Partarum\PartarumPHP\Filesystem\Filesystem;
use Partarum\PartarumPHP\Filesystem\FilesystemGenerator;
use Partarum\PartarumPHP\Helper\Date\DateCalculator;
use Partarum\PartarumPHP\Helper\Date\DateFormatter;
use Partarum\PartarumPHP\Helper\Spreadsheet\Excel;
use Partarum\PartarumPHP\HTTP\API;
use Partarum\PartarumPHP\HTTP\APIRequest;
use Partarum\PartarumPHP\HTTP\APIResponse;
use Partarum\PartarumPHP\HTTP\Cookie;
use Partarum\PartarumPHP\HTTP\CookieObject;
use Partarum\PartarumPHP\HTTP\Data;
use Partarum\PartarumPHP\HTTP\Header;
use Partarum\PartarumPHP\HTTP\Header\Entity\ContentType;
use Partarum\PartarumPHP\HTTP\HeaderObject;
use Partarum\PartarumPHP\HTTP\HTTPHeaderObject;
use Partarum\PartarumPHP\HTTP\Request\Material\Extension;
use Partarum\PartarumPHP\HTTP\Request\Material\Headers;
use Partarum\PartarumPHP\HTTP\Request\REST;
use Partarum\PartarumPHP\HTTP\Request\Router;
use Partarum\PartarumPHP\HTTP\Request\Routes\Manifest;
use Partarum\PartarumPHP\HTTP\Request\Routes\ManifestPartarum;
use Partarum\PartarumPHP\HTTP\Request\Routes\ManifestProxy;
use Partarum\PartarumPHP\HTTP\Request\Routes\ManifestRoots;
use Partarum\PartarumPHP\HTTP\Response\BodyObject;
use Partarum\PartarumPHP\HTTP\Response\Channel;
use Partarum\PartarumPHP\Security\SafetyService;
use Partarum\PartarumPHP\Security\LicentiamObject;
use Partarum\PartarumPHP\Security\SessionWorker;
use Partarum\PartarumPHP\Security\Token\JWT;
use Partarum\PartarumPHP\Security\Token\JWTPayload;
use Partarum\PartarumPHP\Security\Token\UUID;
use Partarum\PartarumPHP\Service\Binary\FlagFactory;
use Partarum\PartarumPHP\Service\Binary\FlagWorker;
use Partarum\PartarumPHP\Service\Factory;
use Partarum\PartarumPHP\Service\typeArray;
use Partarum\PartarumPHP\System\EnvLoader;
use Partarum\PartarumPHP\System\Memory\SharedCacheInterface;
use Partarum\PartarumPHP\System\Memory\SharedCatalog;
use Partarum\PartarumPHP\System\Memory\SharedFactory;
use Partarum\PartarumPHP\System\Memory\SharedFileCache;
use Partarum\PartarumPHP\System\Memory\SharedHelper;
use Partarum\PartarumPHP\System\Memory\SharedKeyCache;
use Partarum\PartarumPHP\System\Memory\SharedLastKeyCache;
use Partarum\PartarumPHP\System\Memory\SharedManager;
use Partarum\PartarumPHP\System\Memory\SharedSemaphore;
use Partarum\PartarumPHP\System\Root;
use Partarum\PartarumPHP\System\SystemAccess;
use Partarum\PartarumPHP\Traits\Database\FeatDatabase;
use Partarum\PartarumPHP\Traits\Document\FeatHTMLAttributes;
use Partarum\PartarumPHP\Traits\Document\FeatHTMLgetEtc;
use Partarum\PartarumPHP\Traits\Document\FeatHTMLPage;
use Partarum\PartarumPHP\Traits\FeatTemplateHelper;
use Partarum\PartarumPHP\Traits\Filesystem\FeatFilesystem;
use Partarum\PartarumPHP\Traits\Security\FeatSessionWorker;
use Partarum\PartarumPHP\Traits\Server\FeatControlSystem;
use Partarum\PartarumPHP\Office;
use Partarum\PartarumPHP\WebApp;
use Partarum\PartarumSurface\Stages\ExecutionStageInterface;
use Partarum\PartarumSurface\Stages\FinalStageInterface;
use Partarum\PartarumSurface\Stages\InitialStageInterface;
use Partarum\PartarumSurface\Stages\SetupStageInterface;
use Partarum\PartarumSurface\Stages\TransitionStageInterface;
use Predis\Client;
use Predis\Response\Status;

$rootDir = rtrim(dirname(__DIR__), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

(!defined("ROOT")) && define("ROOT", $rootDir);
(!defined("SLASH")) && define("SLASH", DIRECTORY_SEPARATOR);
(!defined("PARTARUM_PATH")) && define("PARTARUM_PATH", $rootDir . "Partarum");

$passed = 0;
$failed = 0;

$check = static function (bool $condition, string $label) use (&$passed, &$failed): void {
    if ($condition) {
        $passed++;
        echo "[OK]   " . $label . PHP_EOL;
    } else {
        $failed++;
        fwrite(STDERR, "[FAIL] " . $label . PHP_EOL);
    }
};

/**
 * In-Memory Redis-Double für unabhängige Offline-Smoke-Tests.
 */
final class InMemoryRedisClient extends Client {

    public array $store = [];
    public array $ttls = [];

    public function __construct() {
        // Kein Netzwerk-Socket öffnen
    }

    public function __call($commandID, $arguments) {
        $cmd = strtolower((string)$commandID);
        return match ($cmd) {
            "exists" => array_key_exists((string)$arguments[0], $this->store) ? 1 : 0,
            "get" => $this->store[(string)$arguments[0]] ?? null,
            "set" => $this->handleSet($arguments),
            default => null
        };
    }

    private function handleSet(array $arguments): Status {
        $key = (string)$arguments[0];
        $this->store[$key] = (string)$arguments[1];
        if (isset($arguments[2], $arguments[3]) && strtoupper((string)$arguments[2]) === "EX") {
            $this->ttls[$key] = (int)$arguments[3];
        }
        return Status::get("OK");
    }
}

/**
 * In-Memory PDO- und PDOStatement-Doubles für Offline-Smoke-Tests ohne installierten DB-Treiber.
 */
final class InMemoryPDOStatement extends PDOStatement {

    public string $query;
    public array $bound = [];
    private array $rows;

    public function __construct(string $query = "", array $rows = []) {
        $this->query = $query;
        $this->rows = $rows;
    }

    public function bindValue(string|int $param, mixed $value, int $type = PDO::PARAM_STR): bool {
        $this->bound[$param] = ["value" => $value, "type" => $type];
        return true;
    }

    public function execute(?array $params = null): bool {
        return true;
    }

    public function fetchAll(int $mode = PDO::FETCH_DEFAULT, mixed ...$args): array {
        if ($mode === PDO::FETCH_NUM) {
            return array_map(static fn(array $row): array => array_values($row), $this->rows);
        }
        return $this->rows;
    }

    public function fetch(int $mode = PDO::FETCH_DEFAULT, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed {
        if ($this->rows === []) {
            return false;
        }
        $first = $this->rows[0];
        return ($mode === PDO::FETCH_NUM) ? array_values($first) : $first;
    }
}

final class InMemoryPDO extends PDO {

    public array $executedSql = [];
    public ?InMemoryPDOStatement $lastStatement = null;
    public array $mockRows = [];

    public function __construct() {
        // Keine echte Treiber-Verbindung öffnen
    }

    public function exec(string $statement): int|false {
        $this->executedSql[] = $statement;
        return 1;
    }

    public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs): PDOStatement|false {
        $this->executedSql[] = $query;
        if (str_contains(strtoupper($query), "SHOW DATABASES")) {
            return new InMemoryPDOStatement($query, [
                [0 => "information_schema"],
                [0 => "mysql"],
                [0 => "partarum_app"]
            ]);
        }
        return new InMemoryPDOStatement($query, $this->mockRows);
    }

    public function prepare(string $query, array $options = []): PDOStatement|false {
        $this->executedSql[] = $query;
        $this->lastStatement = new InMemoryPDOStatement($query, $this->mockRows);
        return $this->lastStatement;
    }
}

echo "=== Partarum Smoke Test ===" . PHP_EOL;

// 1. PSR-4 Autoloading (Klassen, Traits, Interfaces, Enums)
$autoloadClasses = [
    Partarum::class,
    EnvLoader::class,
    SystemAccess::class,
    RedisCache::class,
    Root::class,
    GlobalConfig::class,
    WebApp::class,
    CacheConnect::class,
    DatabaseData::class,
    DatabaseDataObject::class,
    DatabaseMysqli::class,
    DatabasePDO::class,
    FlagWorker::class,
    API::class,
    APIRequest::class,
    APIResponse::class,
    Cookie::class,
    CookieObject::class,
    Data::class,
    Header::class,
    HeaderObject::class,
    HTTPHeaderObject::class,
    ContentType::class,
    Channel::class,
    BodyObject::class,
    HTMLPage::class,
    Router::class,
    Extension::class,
    Headers::class,
    REST::class,
    Manifest::class,
    ManifestRoots::class,
    ManifestPartarum::class,
    ManifestProxy::class,
    SafetyService::class,
    SessionWorker::class,
    JWT::class,
    JWTPayload::class,
    UUID::class,
    LicentiamObject::class,
    Filesystem::class,
    FilesystemGenerator::class,
    DirectoryGenerator::class,
    DirectoryBridgeGenerator::class,
    SharedMemory::class,
    WorkerMap::class,
    Factory::class,
    FlagFactory::class,
    SharedCatalog::class,
    SharedFactory::class,
    SharedFileCache::class,
    SharedHelper::class,
    SharedKeyCache::class,
    SharedLastKeyCache::class,
    SharedManager::class,
    SharedSemaphore::class,
    typeArray::class,
    LoremLicentiam::class
];

foreach ($autoloadClasses as $className) {
    $check(class_exists($className), "Autoload class: {$className}");
}

$autoloadTraits = [
    FeatHTMLPage::class,
    FeatHTMLAttributes::class,
    FeatHTMLgetEtc::class,
    FeatDatabase::class,
    FeatFilesystem::class,
    FeatSessionWorker::class,
    FeatTemplateHelper::class,
    FeatControlSystem::class
];

foreach ($autoloadTraits as $traitName) {
    $check(trait_exists($traitName), "Autoload trait: {$traitName}");
}

$autoloadInterfaces = [
    InitialStageInterface::class,
    SetupStageInterface::class,
    TransitionStageInterface::class,
    ExecutionStageInterface::class,
    FinalStageInterface::class,
    SharedCacheInterface::class
];

foreach ($autoloadInterfaces as $interfaceName) {
    $check(interface_exists($interfaceName), "Autoload interface: {$interfaceName}");
}

// 2. EnvLoader
$securityEnv = EnvLoader::load(ROOT);
$check(is_array($securityEnv) && isset($securityEnv["REDIS_IP"]), "EnvLoader::load() lädt PartarumIntern/Security/.env");

// 3. SystemAccess & goDown()
$cwdBefore = getcwd();
$redis = new InMemoryRedisClient();
SystemAccess::init($redis, "smoke:partarum", ROOT . "Partarum/Partarum.php");
$cwdAfter = getcwd();

$check($cwdBefore === $cwdAfter, "SystemAccess::init() stellt das Arbeitsverzeichnis wieder her");
$check(isset($redis->store["smoke:partarum:system:access"]), "SystemAccess::init() legt :system:access in Redis ab");
$accessPayload = json_decode($redis->store["smoke:partarum:system:access"] ?? "{}", true);
$check(is_array($accessPayload) && isset($accessPayload["scanArray"], $accessPayload["user"]), "SystemAccess speichert scanArray und User-Kontext");

// 4. RedisCache
$status = RedisCache::set($redis, "smoke:user", "greeting", "hallo", 120);
$check((string)$status === "OK", "RedisCache::set() liefert Status OK");
$check(RedisCache::has($redis, "smoke:user", "greeting") === true, "RedisCache::has() erkennt gesetzten Key");
$check(RedisCache::get($redis, "smoke:user", "greeting") === "hallo", "RedisCache::get() liest gesetzten Wert");
$check(($redis->ttls["smoke:user:cache:greeting"] ?? 0) === 120, "RedisCache::set() setzt TTL bei expires > 0");

// 5. Router & Manifest
$idInfo = [
    "idKey" => "smoke:user",
    "partarumKey" => "smoke:partarum",
    "manifests" => []
];

Router::initRouting("smoke:user", Router::DEFAULT_USER_MANIFESTS, $redis, $idInfo);
$check(count($idInfo["manifests"]) > 0, "Router::initRouting() registriert Standard-Manifeste");
$check(
    Router::getRoutingKey("/Partarum/Workspace", $idInfo) === "smoke:partarum:routes:Partarum/Workspace",
    "Router::getRoutingKey() routet Partarum-Pfade auf partarumKey"
);
$check(
    Router::getRoutingKey("/landingPage", $idInfo) === "smoke:user:routes:landingPage",
    "Router::getRoutingKey() routet App-Pfade auf idKey"
);
$check(
    ManifestProxy::getPath(["", "assets"], null) === "config/assets-manifest.json",
    "ManifestProxy::getPath() löst Array-Segmente korrekt auf"
);

// 6. Extension & Headers
$headers = Headers::setHeader("WEBP");
$check($headers->ext === "webp" && $headers->headers["webp"]["content"] === "image/webp", "Headers normalisiert Dateiendung und kennt moderne MIME-Types");

http_response_code(200);
Extension::action(ROOT . "nonexistent.file", "exe");
$check(http_response_code() === 404, "Extension::action() blockiert unerlaubte Endungen mit 404");

// 7. SafetyService, JWT & JWTPayload
$emails = iterator_to_array(SafetyService::isEmail(["test@example.com", "invalid-email"]));
$check($emails[0] === "test@example.com" && $emails[1] === false, "SafetyService::isEmail() validiert E-Mail-Listen korrekt");

$equals = iterator_to_array(SafetyService::isEqualValue(["a" => "secret", "b" => "1"], ["a" => "secret", "b" => 1]));
$check($equals[0] === "secret" && $equals[1] === false, "SafetyService::isEqualValue() prüft strikt und timing-sicher");

$sanitized = SafetyService::makeSafetyString("line1\\r\\nline2");
$check($sanitized === "line1FALSEFALSEline2", "SafetyService::makeSafetyString() ersetzt alle Steuerzeichen-Sequenzen");

$jwtToken = SafetyService::encodeJWT(["userId" => "42", "role" => "admin"], "unitTestSecret");
$check(SafetyService::decodeJWT($jwtToken, "unitTestSecret") === true, "SafetyService::encodeJWT() / decodeJWT() verifiziert gültigen Token");
$check(SafetyService::decodeJWT($jwtToken, "wrongSecret") === false, "SafetyService::decodeJWT() weist falschen Schlüssel ab");

$payload = new JWTPayload();
$payload->tokenData = "geheimerInhalt";
$check($payload->tokenData === "geheimerInhalt" && isset($payload->tokenData), "JWTPayload ver- und entschlüsselt Werte mit individuellem IV");

// 8. REST
$decodedJson = REST::convertData('{"status":"ok"}');
$check(is_object($decodedJson) && $decodedJson->status === "ok", "REST::convertData() dekodiert valides JSON");
$check(REST::convertData("kein-json") === false, "REST::convertData() fängt invalides JSON sauber mit false ab");

// 9. GlobalConfig
GlobalConfig::setTheme("partarum");
GlobalConfig::setWrapper(true);
$check(GlobalConfig::$theme === "partarum" && GlobalConfig::$wrapper === true, "GlobalConfig speichert typisierte Einstellungen");

// 10. HTTP API, Data, Cookie, CookieObject & SessionWorker
$_GET = ["filter" => "active"];
$_POST = ["action" => "save"];
$_SERVER["HTTP_X_PARTARUM_TEST"] = "42";
$_SERVER["SERVER_PORT"] = "80";
unset($_SERVER["REQUEST_SCHEME"], $_SERVER["HTTPS"]);

$api = new API();
$check($api->fromGET("filter") === "active", "API::fromGET() liest GET-Parameter");
$check($api->fromPOST("action") === "save", 'API::fromPOST() behält $_POST bei leerem php://input');
$check($api->fromHeader("X-Partarum-Test", Data::REQUEST) === "42", "API::fromHeader() liest Request-Header auch im CLI-Fallback");
$check($api->getRequestScheme() === "http", "API::getRequestScheme() erkennt String-Port '80' als http");
$_SERVER["SERVER_PORT"] = "443";
$check($api->getRequestScheme() === "https", "API::getRequestScheme() erkennt Port '443' als https");

$apiReq = $api->createRequest("https://example.org/api");
$apiReq->setParams(["a" => 1, "b" => 2]);
$apiReq->setParams(["page" => 3]);
$check($apiReq->getParams() === "?page=3", "APIRequest::setParams() setzt Query-Parameter sauber zurück");
$check($api->getRequest($apiReq->id) === $apiReq, "API::getRequest() liefert gecachten APIRequest");

$jsonResp = $api->createJSONResponse(["ok" => true]);
$check($jsonResp->channel->headerObject->contentType === "json", "API::createJSONResponse() registriert Content-Type 'json' im HeaderObject");
ob_start();
$jsonResp->run(201);
$jsonOut = (string)ob_get_clean();
$check($jsonOut === '[{"ok":true}]', "APIResponse::run() gibt JSON-Body fehlerfrei aus");

$check(ContentType::getOutput("json") === "Content-Type: application/json", "ContentType::getOutput() liefert Header-Zeile für bekannte Typen");
$check(ContentType::getOutput("unbekannt") === false, "ContentType::getOutput() fängt unbekannte Konstanten ohne Error ab");

$cookieAssoc = new Cookie(["name" => "sid", "value" => "xyz", "lifetime" => 3600]);
$check($cookieAssoc->getName() === "sid" && $cookieAssoc->value === "xyz" && $cookieAssoc->lifetime === 3600, "Cookie-Konstruktor verarbeitet assoziatives Array");

$cookiePos = new Cookie("theme", "dark", 7200, "/app");
$check($cookiePos->getName() === "theme" && $cookiePos->value === "dark" && $cookiePos->path === "/app", "Cookie-Konstruktor verarbeitet positionale Parameter");
$cookieId = $cookiePos->create();
$check(strlen($cookieId) === 5 && ctype_xdigit($cookieId), "Cookie::create() erzeugt 5-stellige Hex-ID ohne Dateisystem-Warnung");

$_COOKIE["theme"] = "dark";
$check($cookiePos->kill() === true && !isset($_COOKIE["theme"]), "Cookie::kill() / destroy() entfernt gesetzten Cookie");

$cookieObj1 = new CookieObject(["name" => "c1", "value" => "v1", "expire" => 60]);
$cookieObj2 = new CookieObject("c2", "v2", 120, "/", "example.org", true, true, Cookie::STRICT);
$check($cookieObj1->getName() === "c1" && $cookieObj2->getSamesite() === Cookie::STRICT, "CookieObject unterstützt Array- und 8-Parameter-Konstruktor");

$sessionWorker = new SessionWorker(false);
$check($sessionWorker->sessionOpen === false && $sessionWorker->close() === true, "SessionWorker lädt Trait FeatSessionWorker fehlerfrei");

// 11. Database Layer (DatabaseDataObject, DatabaseData, DatabaseMysqli, FeatDatabase, DatabasePDO & CacheConnect)
$dbObj = new DatabaseDataObject();
$check(
    $dbObj->getDatabaseType() === "MySQL" && $dbObj->getHost() === "localhost" && $dbObj->getDatabaseName() === "",
    "DatabaseDataObject liefert sichere Default-Werte ohne uninitialized-Property-Error"
);

$dbData = new DatabaseData();
$loadedData = $dbData->getData();
$check(
    $loadedData instanceof DatabaseDataObject && $loadedData->getDatabaseType() === "MySQL",
    "DatabaseData lädt .DB.json (inkl. 'DatabaseTyp'-Key) ohne echo-Ausgabe"
);

$mysqliDb = new DatabaseMysqli();
$mysqliDb->setHost("127.0.0.1");
$mysqliDb->setDatabase("partarum_test");
$mysqliDb->setUsername("partarum_user");
$mysqliDb->setPassword("secret");
$check(
    $mysqliDb->getHost() === "127.0.0.1" && $mysqliDb->getDatabase() === "partarum_test",
    "DatabaseMysqli speichert Verbindungsdaten typsicher"
);
$check(
    $mysqliDb->getTableMission(["table" => "users; DROP TABLE x", "select" => "id, name"], "base") === "SELECT id, name FROM usersDROPTABLEx",
    "FeatDatabase::getTableMission() bereinigt Tabellennamen und erzeugt SELECT-Query"
);
$escaped = iterator_to_array($mysqliDb->realEscape(["a'b", "c"]));
$check($escaped[0] === "a\\'b" && $escaped[1] === "c", "DatabaseMysqli::realEscape() escaped Strings auch ohne offene DB-Verbindung");
$check($mysqliDb->read("SELECT 1")->current() === false, "DatabaseMysqli::read() gibt ohne Verbindung sauber false zurück");
$check($mysqliDb->readWithParams("SELECT ?", [1], "i") === false, "DatabaseMysqli::readWithParams() gibt ohne Verbindung sauber false zurück");
$check($mysqliDb->write("INSERT INTO t VALUES (1)") === false, "DatabaseMysqli::write() gibt ohne Verbindung sauber false zurück");
$check($mysqliDb->close() === true, "DatabaseMysqli::close() schließt sauber auch ohne aktive Verbindung");

$pdoDb = new DatabasePDO();
$pdoDb->setDatabaseType("mysql");
$pdoDb->setHost("db.example.internal");
$pdoDb->setDatabase("partarum_prod");
$pdoDb->setUsername("partarum");
$pdoDb->setPassword("geheim");
$check(
    $pdoDb->getDsn() === "mysql:host=db.example.internal;dbname=partarum_prod;charset=utf8mb4",
    "DatabasePDO::getDsn() baut MySQL-DSN mit utf8mb4 korrekt zusammen"
);

$inMemoryPdo = new InMemoryPDO();
$pdoDb->dbase = $inMemoryPdo;

$check(
    $pdoDb->write("CREATE TABLE items (id INT PRIMARY KEY, name VARCHAR(50), qty INT)") === true,
    "DatabasePDO::write() führt DDL-Statement über PDO::exec() aus"
);
$check(
    $pdoDb->writeWithParams("INSERT INTO items (name, qty) VALUES (?, ?)", ["Schraube", 25], "si") === true
    && $inMemoryPdo->lastStatement !== null
    && $inMemoryPdo->lastStatement->bound[1]["value"] === "Schraube"
    && $inMemoryPdo->lastStatement->bound[2]["value"] === 25
    && $inMemoryPdo->lastStatement->bound[2]["type"] === PDO::PARAM_INT,
    "DatabasePDO::writeWithParams() bindet Parameter typsicher per Prepared Statement"
);

$inMemoryPdo->mockRows = [
    ["name" => "Schraube", "qty" => 25]
];
$singleRow = $pdoDb->read([
    "item" => [
        "sql" => "SELECT name, qty FROM items WHERE name = ?",
        "params" => ["Schraube"]
    ]
], "assoc")->current();
$check(
    is_array($singleRow) && $singleRow["name"] === "Schraube" && $singleRow["qty"] === 25,
    "DatabasePDO::read() unterstützt parametrisierte Prepared-Queries und gibt Einzelzeile zurück"
);

$inMemoryPdo->mockRows = [
    ["name" => "Schraube", "qty" => 25],
    ["name" => "Mutter", "qty" => 50]
];
$multiRows = $pdoDb->readWithParams("SELECT name, qty FROM items WHERE qty >= ? ORDER BY id ASC", [10], "i");
$check(
    is_array($multiRows) && count($multiRows) === 2 && $multiRows[1]["name"] === "Mutter",
    "DatabasePDO::readWithParams() liefert alle passenden Datensätze als Array"
);
$check(
    $pdoDb->showDatabase(false) === ["partarum_app"] && $pdoDb->showDatabase("partarum_app") === "partarum_app",
    "FeatDatabase::showDatabase() filtert System-Datenbanken ohne \$GLOBALS-Seiteneffekte"
);
$check($pdoDb->close() === true && $pdoDb->dbase === null, "DatabasePDO::close() schließt PDO-Verbindung sauber");

$tempDbJson = sys_get_temp_dir() . "/partarum_test_db_" . uniqid("", true) . ".json";
file_put_contents($tempDbJson, json_encode([
    "DatabaseTyp" => "PDO_MySQL",
    "DatabaseName" => "custom_db",
    "Host" => "10.0.0.5",
    "Username" => "db_admin",
    "Password" => "topsecret"
]));
$customDbData = (new DatabaseData($tempDbJson))->getData();
@unlink($tempDbJson);
$check(
    $customDbData->getDatabaseType() === "PDO_MySQL"
    && $customDbData->getDatabaseName() === "custom_db"
    && $customDbData->getHost() === "10.0.0.5"
    && $customDbData->getUsername() === "db_admin",
    "DatabaseData liest benutzerdefinierte JSON-Konfiguration vollständig ein"
);

$cacheConnect = new CacheConnect(CacheConnect::DATABASE);
$check(
    $cacheConnect->isFlagSet(CacheConnect::DATABASE) === true && $cacheConnect->isFlagSet(CacheConnect::SESSION) === false,
    "CacheConnect verwaltet Bitflags über FlagWorker korrekt"
);
$cachePdo = new DatabasePDO();
$cachePdo->dbase = new InMemoryPDO();
$cachePdo->dbase->mockRows = [["label" => "aktiv"]];
$cacheConnect->db = $cachePdo;
$cacheConnect->connect = true;
$check(
    $cacheConnect->writeWithParams("INSERT INTO test_cc (label) VALUES (?)", ["aktiv"], "s") === true,
    "CacheConnect delegiert writeWithParams() an den aktiven Treiber"
);
$ccRows = $cacheConnect->readWithParams("SELECT label FROM test_cc WHERE id = ?", [1], "i");
$check(
    is_array($ccRows) && ($ccRows[0]["label"] ?? "") === "aktiv",
    "CacheConnect delegiert readWithParams() an den aktiven Treiber"
);
$check($cacheConnect->close() === true && $cacheConnect->connect === false, "CacheConnect::close() schließt Verbindung und setzt connect = false");

// 12. Document & DOM Generator Engine
$docClasses = [
    HTMLArticle::class,
    HTMLAttribute::class,
    HTMLCollection::class,
    HTMLDocument::class,
    HTMLDom::class,
    HTMLElement::class,
    HTMLetcElementInfo::class,
    HTMLFamily::class,
    HTMLfooter::class,
    HTMLgetEtc::class,
    HTMLhead::class,
    HTMLheader::class,
    HTMLmain::class,
    HTMLObject::class,
    HTMLPage::class,
    HTMLPlain::class,
    HTMLScript::class,
    HTMLSeries::class,
    HTMLService::class,
    HTMLText::class,
    NodeCollection::class,
    NodeObject::class,
    Office::class
];
foreach ($docClasses as $docClass) {
    $check(class_exists($docClass), "Document-Klasse geladen: {$docClass}");
}

$htmlDoc = new HTMLDocument();
$check(
    $htmlDoc->head instanceof DOMElement && $htmlDoc->body instanceof DOMElement && $htmlDoc->body->getAttribute("class") === "",
    "HTMLDocument initialisiert head & body ohne Dummy-Klasse"
);

$nodeObj = new NodeObject("section");
$nodeObj->id = "heroSection";
$nodeObj->class = "container hero";
$nodeObj->text = "Willkommen bei Partarum";
$heroEl = new HTMLElement($nodeObj, $htmlDoc->body);
$check(
    $heroEl->getAttribute("id") === "heroSection"
    && $heroEl->getAttribute("class") === "container hero"
    && $heroEl->textContent === "Willkommen bei Partarum"
    && $nodeObj->element === "section",
    "HTMLElement verarbeitet NodeObject inkl. Attributen und Text ohne Mutation des NodeObjects"
);

$series = new HTMLSeries("div", $heroEl, 2);
$series->quantity = 3;
$series->id = ["box_1", "box_2", "box_3"];
$series->text = ["Eins", "Zwei", "Drei"];
$check(
    $series->length === 3
    && count($series->elements) === 3
    && $series->box_2 instanceof HTMLElement
    && $series->box_2->textContent === "Zwei",
    "HTMLSeries erstellt Kindelemente für HTMLElement-Parent, setzt Attribute/Texte und löst IDs via __get auf"
);

$family = new HTMLFamily("nav", $heroEl);
$family->id = "mainNav";
$family->setChild("ul");
$family->setGrandChildren("li", 0, 4);
$check(
    count($family->children) === 1
    && count($family->children[0]->children) === 4,
    "HTMLFamily verwaltet Eltern-, Kind- und Enkel-Elemente über \$children"
);

ob_start();
$page = new HTMLPage();
$page->openWithWrapper();
$page->openWithWrapper(); // Idempotenz-Prüfung: darf keinen zweiten globalHeader erzeugen
$page->setTitle("Partarum Startseite – Übersicht");
$page->setLink("/public/css/main.css");
$page->setScript("/public/js/app.js");
$pageOut = (string)ob_get_clean();
$pageHtml = $page->getHTML();
$decodedPageHtml = html_entity_decode($pageHtml, ENT_QUOTES | ENT_HTML5, "UTF-8");
$check($pageOut === "", "HTMLPage::__construct() und openWithWrapper(true) geben noch keinen HTML-Output vor close() aus");
$check(
    substr_count($pageHtml, 'id="globalHeader"') === 1
    && substr_count($pageHtml, 'id="content"') === 1
    && substr_count($pageHtml, 'id="globalFooter"') === 1
    && str_contains($decodedPageHtml, "<title>Partarum Startseite – Übersicht</title>")
    && str_contains($pageHtml, 'href="/public/css/main.css"')
    && str_contains($pageHtml, 'src="/public/js/app.js"'),
    "HTMLPage erzeugt genau einen Wrapper (header/main/footer) und setzt UTF-8-Title, Link sowie Script"
);

$plain = new HTMLPlain();
$plain->setTitle("Plain Page");
$plain->setScript("/app.mjs", true);
$plainHtml = $plain->getHTML();
$check(
    str_contains($plainHtml, "<title>Plain Page</title>") && str_contains($plainHtml, 'type="module"'),
    "HTMLPlain erzeugt eigenständiges HTML5-Dokument mit Modul-Script und Viewport"
);

$ids = iterator_to_array(HTMLService::createID("item", 2));
$check($ids === ["item_0", "item_1", "item_2"], "HTMLService::createID() generiert fortlaufende Element-IDs");

$domHelper = new HTMLDom();
$domHelper->customKey = "surface/custom";
$check(
    $domHelper->getPath("customKey") === "surface/custom" && isset($domHelper->customKey),
    "HTMLDom speichert und liest dynamische Pfade ohne Deprecation-Warnung"
);

$tempArticleFile = sys_get_temp_dir() . "/partarum_article_" . uniqid("", true) . ".txt";
file_put_contents($tempArticleFile, "<->\nÜberschrift im Artikel\n</->\n");
$article = new HTMLArticle($tempArticleFile, $series);
@unlink($tempArticleFile);
$decodedDocHtml = html_entity_decode($htmlDoc->getHTML(), ENT_QUOTES | ENT_HTML5, "UTF-8");
$check(
    $article->articleDom instanceof HTMLElement
    && str_contains($decodedDocHtml, "Überschrift im Artikel"),
    "HTMLArticle parst Markup-Datei in <article>-DOM-Baum ohne utf8_encode-Deprecation"
);

// 13. Helper (DateFormatter, DateCalculator, Excel) & API::fromBearerToken()
$check(
    DateCalculator::isNegative(-5) === true && DateCalculator::isNegative(0) === false && DateCalculator::isNegative(3.14) === false,
    "DateCalculator::isNegative() erkennt negative Zahlen korrekt"
);

$formattedDeSingle = DateFormatter::format_DE("2026-11-16", "yyyy-MM-dd", "dd.MM.yyyy");
$check(
    $formattedDeSingle === "16.11.2026",
    "DateFormatter::format_DE() formatiert Einzel-Pattern ins deutsche Datumsformat"
);

$formattedDeMulti = DateFormatter::format_DE("2026-11-16", "yyyy-MM-dd", [
    "date" => "yyyy-MM-dd",
    "datetime" => "yyyy-MM",
    "displayDate" => "MMMM yyyy"
]);
$check(
    is_array($formattedDeMulti)
    && $formattedDeMulti["date"] === "2026-11-16"
    && $formattedDeMulti["datetime"] === "2026-11"
    && str_contains(strtolower($formattedDeMulti["displayDate"]), "november 2026"),
    "DateFormatter::format_DE() gibt assoziatives Array bei Array-Schema zurück"
);
$check(
    DateFormatter::format_DE("ungueltig", "yyyy-MM-dd", "dd.MM.yyyy") === "",
    "DateFormatter::format_DE() fängt ungültige Datumsangaben ohne FatalError ab"
);

$excelInput26 = range(1, 26);
$excelMapped26 = Excel::getCharacter($excelInput26);
$check(
    count($excelMapped26) === 26
    && array_key_first($excelMapped26) === "A"
    && array_key_last($excelMapped26) === "Z"
    && $excelMapped26["Z"] === 26,
    "Excel::getCharacter() mappt genau 26 Spalten auf A bis Z (ohne Off-by-One bei 25)"
);

$excelInput30 = range(1, 30);
$excelMapped30 = Excel::getCharacter($excelInput30);
$check(
    count($excelMapped30) === 30
    && ($excelMapped30["AA"] ?? null) === 27
    && ($excelMapped30["AD"] ?? null) === 30,
    "Excel::getCharacter() mappt Spalten ab Index 27 auf AA, AB, AC, AD"
);
$check(Excel::getCharacter([]) === [], "Excel::getCharacter() gibt bei leerem Array [] zurück");

unset($_SERVER["HTTP_AUTHORIZATION"], $_SERVER["REDIRECT_HTTP_AUTHORIZATION"]);
$check($api->fromBearerToken() === null, "API::fromBearerToken() liefert null wenn kein Authorization-Header gesetzt ist");

$_SERVER["HTTP_AUTHORIZATION"] = "Basic dXNlcjpwYXNz";
$check($api->fromBearerToken() === null, "API::fromBearerToken() liefert null bei Nicht-Bearer-Authorization-Header");

$_SERVER["HTTP_AUTHORIZATION"] = "Bearer my-secret-jwt-token-123";
$check(
    $api->fromBearerToken() === "my-secret-jwt-token-123",
    "API::fromBearerToken() extrahiert Bearer-Token zuverlässig aus dem Authorization-Header"
);

// 14. Filesystem, UUID (RFC 4122), WorkerMap/Factory & SharedMemory-Helper
Filesystem::clearCache();
$fsTestPath = ROOT . "Partarum/PartarumPHP/Helper";
$fsInstance = new Filesystem($fsTestPath, Filesystem::RECURSIVE | Filesystem::DOCUMENT_ROOT);
$fsFiles = $fsInstance->getAllFiles();
$check(
    count($fsFiles) >= 3
    && in_array("Partarum/PartarumPHP/Helper/Spreadsheet/Excel.php", $fsFiles, true),
    "Filesystem scannt Verzeichnisse rekursiv und kürzt DOCUMENT_ROOT auch bei uninitialisiertem Root::\$DOCUMENT_ROOT"
);
Filesystem::clearCache();

$fsGen = new FilesystemGenerator($fsTestPath);
$yieldedFiles = [];
foreach ($fsGen->getFile() as $fileObj) {
    $yieldedFiles[] = $fileObj->getFilename();
}
$check(
    in_array("Excel.php", $yieldedFiles, true)
    && in_array("DateFormatter.php", $yieldedFiles, true),
    "FilesystemGenerator / DirectoryGenerator / DirectoryBridgeGenerator liefern rekursiv alle Dateien"
);

$uuidV4 = (string)UUID::v4()->current();
$check(
    UUID::is_valid($uuidV4)
    && $uuidV4[14] === "4"
    && in_array(strtolower($uuidV4[19]), ["8", "9", "a", "b"], true),
    "UUID::v4() erzeugt RFC-4122-konforme v4-UUID (Version-Bit 4 & Variant-Bit 8/9/a/b)"
);

$uuidV3 = UUID::v3($uuidV4, "partarum-test");
$uuidV5 = UUID::v5($uuidV4, "partarum-test");
$check(
    is_string($uuidV3) && UUID::is_valid($uuidV3) && $uuidV3[14] === "3"
    && is_string($uuidV5) && UUID::is_valid($uuidV5) && $uuidV5[14] === "5"
    && UUID::v3("invalid-uuid", "test") === false,
    "UUID::v3() und UUID::v5() erzeugen valide deterministische UUIDs und weisen ungültige Namespaces ab"
);

$check(
    isset(LicentiamObject::PROJECT["verificationPage"]),
    "LicentiamObject stellt Projekt-Konstanten typsicher bereit"
);

ob_start();
$createdWorker = Factory::createWorker();
$factoryOut = (string)ob_get_clean();
$check(
    $createdWorker instanceof FlagWorker
    && Factory::checkWorker($createdWorker) === true
    && $factoryOut === "",
    "Factory::createWorker() & WorkerMap registrieren Worker in WeakMap ohne Debug-Output"
);
Factory::deleteWorker($createdWorker);
$check(
    Factory::checkWorker($createdWorker) === false,
    "Factory::deleteWorker() entfernt Worker sauber aus der WeakMap"
);

$shmJson = SharedHelper::dataToJSON(["key" => "wert", "count" => 7]);
$shmArray = SharedHelper::jsonToArray(($shmJson ?? "") . "\x00\x00");
$check(
    is_string($shmJson)
    && is_array($shmArray)
    && ($shmArray["key"] ?? null) === "wert"
    && SharedHelper::jsonToArray("\x00\x00") === null
    && SharedHelper::createHexString(0x11223344) === "0x11223344",
    "SharedHelper konvertiert Daten nach JSON, bereinigt Null-Bytes aus Shared-Memory-Strings und formatiert Hex-Keys"
);

echo str_repeat("-", 35) . PHP_EOL;
echo "Ergebnis: {$passed} erfolgreich, {$failed} fehlgeschlagen." . PHP_EOL;

exit($failed === 0 ? 0 : 1);
