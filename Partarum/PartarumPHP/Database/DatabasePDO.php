<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Database {

    use Generator;
    use Partarum\PartarumPHP\Traits\Database\FeatDatabase;
    use PDO;
    use PDOStatement;
    use Throwable;

    class DatabasePDO {

        use FeatDatabase;

        public ?PDO $dbase = null;
        public ?string $lastError = null;

        private string $databaseType = "mysql";
        private string $host = "localhost";
        private string $username = "";
        private string $password = "";
        private string $database = "";
        private ?string $dsn = null;

        public function __construct(?string $dsn = null) {
            $this->dsn = $dsn;
        }

        public function getDatabaseType(): string {
            return $this->databaseType;
        }

        public function setDatabaseType(string $databaseType): void {
            $this->databaseType = $databaseType;
        }

        public function getHost(): string {
            return $this->host;
        }

        public function setHost(string $host): void {
            $this->host = $host;
        }

        public function getUsername(): string {
            return $this->username;
        }

        public function setUsername(string $username): void {
            $this->username = $username;
        }

        public function getPassword(): string {
            return $this->password;
        }

        public function setPassword(string $password): void {
            $this->password = $password;
        }

        public function getDatabase(): string {
            return $this->database;
        }

        public function setDatabase(string $database): void {
            $this->database = $database;
        }

        public function setDsn(string $dsn): void {
            $this->dsn = $dsn;
        }

        public function getDsn(): string {

            if ($this->dsn !== null && $this->dsn !== "") {
                return $this->dsn;
            }

            $driver = strtolower(trim($this->databaseType));

            if ($driver === "sqlite") {
                $target = $this->database !== "" ? $this->database : ":memory:";
                return "sqlite:" . $target;
            }

            if ($driver === "mysql" || $driver === "mariadb" || $driver === "") {
                $driver = "mysql";
            }

            return "{$driver}:host={$this->host};dbname={$this->database};charset=utf8mb4";
        }

        public function open(?array $item = null): bool {

            $this->lastError = null;

            try {
                if (is_array($item)) {
                    if (isset($item["dsn"])) {
                        $dsn = (string)$item["dsn"];
                        $user = isset($item["username"]) ? (string)$item["username"] : null;
                        $pass = isset($item["password"]) ? (string)$item["password"] : null;
                    } else {
                        $host = (string)($item[0] ?? $this->host);
                        $user = (string)($item[1] ?? $this->username);
                        $pass = (string)($item[2] ?? $this->password);
                        $db   = (string)($item[3] ?? $this->database);

                        $this->host = $host;
                        $this->username = $user;
                        $this->password = $pass;
                        $this->database = $db;
                        $dsn = $this->getDsn();
                    }
                } else {
                    $dsn = $this->getDsn();
                    $user = $this->username !== "" ? $this->username : null;
                    $pass = $this->password !== "" ? $this->password : null;
                }

                $this->dbase = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);

                return true;
            } catch (Throwable $e) {
                $this->lastError = $e->getMessage();
                $this->dbase = null;
                error_log("[Partarum][DatabasePDO] Connection failed: " . $this->lastError);
                return false;
            }
        }

        public function read(array|string $item, string $mode = "both", bool $column = false): Generator {

            $query = is_array($item) ? $item : [$item];

            $fetchMode = match ($mode) {
                "num" => PDO::FETCH_NUM,
                "assoc" => PDO::FETCH_ASSOC,
                default => PDO::FETCH_BOTH
            };

            foreach ($query as $key => $value) {

                if (!($this->dbase instanceof PDO)) {
                    yield $key => false;
                    continue;
                }

                try {
                    if (is_array($value) && isset($value["sql"])) {
                        $sql = (string)$value["sql"];
                        $params = isset($value["params"]) && is_array($value["params"]) ? $value["params"] : [];
                        $stmt = $this->executePrepared($sql, $params);
                    } else {
                        $stmt = $this->dbase->query((string)$value);
                    }
                } catch (Throwable $e) {
                    $this->lastError = $e->getMessage();
                    error_log("[Partarum][DatabasePDO] Read error: " . $this->lastError);
                    $stmt = false;
                }

                if (($stmt instanceof PDOStatement) && ($column === false)) {

                    $rows = $stmt->fetchAll($fetchMode);
                    $count = count($rows);

                    if ($count === 0) {
                        $setResult = false;
                    } elseif ($count === 1) {
                        $setResult = $rows[0];
                    } else {
                        $setResult = $rows;
                    }

                    yield $key => $setResult;

                } elseif (($stmt instanceof PDOStatement) && ($column === true)) {

                    $row = $stmt->fetch(PDO::FETCH_NUM);
                    yield $key => $row;

                } else {
                    yield $key => false;
                }
            }
        }

        public function readWithParams(
            string $query,
            array $valueArray = [],
            ?string $types = null,
            string $mode = "assoc"
        ): array|false {

            if (!($this->dbase instanceof PDO)) {
                return false;
            }

            $fetchMode = match ($mode) {
                "num" => PDO::FETCH_NUM,
                "both" => PDO::FETCH_BOTH,
                default => PDO::FETCH_ASSOC
            };

            try {
                $stmt = $this->executePrepared($query, $valueArray, $types);
                if (!($stmt instanceof PDOStatement)) {
                    return false;
                }

                return $stmt->fetchAll($fetchMode);
            } catch (Throwable $e) {
                $this->lastError = $e->getMessage();
                error_log("[Partarum][DatabasePDO] readWithParams error: " . $this->lastError);
                return false;
            }
        }

        public function write(string $item, array $valueArray = [], ?string $types = null): bool {

            if ($valueArray !== []) {
                return $this->writeWithParams($item, $valueArray, $types);
            }

            if (!($this->dbase instanceof PDO)) {
                return false;
            }

            try {
                return $this->dbase->exec($item) !== false;
            } catch (Throwable $e) {
                $this->lastError = $e->getMessage();
                error_log("[Partarum][DatabasePDO] Write error: " . $this->lastError);
                return false;
            }
        }

        public function writeWithParams(string $query, array $valueArray, ?string $types = null): bool {

            if (!($this->dbase instanceof PDO)) {
                return false;
            }

            try {
                $stmt = $this->executePrepared($query, $valueArray, $types);
                return $stmt instanceof PDOStatement;
            } catch (Throwable $e) {
                $this->lastError = $e->getMessage();
                error_log("[Partarum][DatabasePDO] writeWithParams error: " . $this->lastError);
                return false;
            }
        }

        public function close(): bool {
            $this->dbase = null;
            return true;
        }

        private function executePrepared(string $query, array $valueArray = [], ?string $types = null): PDOStatement|false {

            if (!($this->dbase instanceof PDO)) {
                return false;
            }

            $stmt = $this->dbase->prepare($query);
            if ($stmt === false) {
                return false;
            }

            $isList = array_is_list($valueArray);

            foreach ($valueArray as $index => $val) {
                $paramKey = $isList ? ((int)$index + 1) : (str_starts_with((string)$index, ":") ? (string)$index : ":" . $index);

                $pdoType = PDO::PARAM_STR;
                if ($types !== null && $isList && isset($types[(int)$index])) {
                    $pdoType = match ($types[(int)$index]) {
                        "i" => PDO::PARAM_INT,
                        "b" => PDO::PARAM_LOB,
                        default => PDO::PARAM_STR
                    };
                } elseif (is_int($val)) {
                    $pdoType = PDO::PARAM_INT;
                } elseif (is_bool($val)) {
                    $pdoType = PDO::PARAM_BOOL;
                } elseif ($val === null) {
                    $pdoType = PDO::PARAM_NULL;
                }

                $stmt->bindValue($paramKey, $val, $pdoType);
            }

            return $stmt->execute() ? $stmt : false;
        }
    }
}