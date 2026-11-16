<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Database {

    use Generator;
    use mysqli;
    use mysqli_result;
    use Partarum\PartarumPHP\Traits\Database\FeatDatabase;
    use Throwable;

    class DatabaseMysqli
    {
        use FeatDatabase;

        private const FETCH_ASSOC = 1;
        private const FETCH_NUM   = 2;
        private const FETCH_BOTH  = 3;

        public ?mysqli $dbase = null;
        public mixed $request = null;
        public ?string $lastError = null;

        private string $host = "localhost";
        private string $username = "";
        private string $password = "";
        private string $database = "";

        public function __construct(mixed $item = true) {

        }

        public function setData(): void {

        }

        /**
         * @return string
         */
        public function getHost(): string {

            return $this->host;
        }

        /**
         * @param string $host
         */
        public function setHost(string $host): void {

            $this->host = $host;
        }

        /**
         * @return string
         */
        public function getUsername(): string {

            return $this->username;
        }

        /**
         * @param string $username
         */
        public function setUsername(string $username): void {

            $this->username = $username;
        }

        /**
         * @return string
         */
        public function getPassword(): string {

            return $this->password;
        }

        /**
         * @param string $password
         */
        public function setPassword(string $password): void {

            $this->password = $password;
        }

        /**
         * @return string
         */
        public function getDatabase(): string {

            return $this->database;
        }

        /**
         * @param string $database
         */
        public function setDatabase(string $database): void {

            $this->database = $database;
        }

        public function open(?array $item = null): bool {

            $this->lastError = null;

            if (!extension_loaded("mysqli") || !class_exists(mysqli::class)) {
                $this->lastError = "ext-mysqli is not loaded";
                error_log("[Partarum][DatabaseMysqli] " . $this->lastError);
                return false;
            }

            try {
                if (!isset($item)) {

                    $conn = mysqli_init();

                    if ($conn === false) {
                        $this->lastError = "mysqli_init failed";
                        return false;
                    }

                    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
                    $conn->options(MYSQLI_OPT_READ_TIMEOUT, 10);

                    @$conn->real_connect($this->host, $this->username, $this->password, $this->database);
                    $this->dbase = $conn;

                } else {

                    $host = (string)($item[0] ?? $this->host);
                    $user = (string)($item[1] ?? $this->username);
                    $pass = (string)($item[2] ?? $this->password);
                    $db   = (string)($item[3] ?? $this->database);

                    $this->dbase = @new mysqli($host, $user, $pass, $db);
                }

                if ($this->dbase->connect_errno) {

                    $this->lastError = $this->dbase->connect_error;
                    if (session_status() === PHP_SESSION_ACTIVE) {
                        $_SESSION["dbaseError"] = $this->lastError;
                    }
                    error_log("[Partarum][DatabaseMysqli] Connection error (" . $this->dbase->connect_errno . "): " . $this->lastError);
                    return false;
                }

                if (session_status() === PHP_SESSION_ACTIVE) {
                    unset($_SESSION["dbaseError"], $_SESSION["counter"]);
                }

                return true;

            } catch (Throwable $e) {
                $this->lastError = $e->getMessage();
                error_log("[Partarum][DatabaseMysqli] Exception during open(): " . $this->lastError);
                return false;
            }
        }

        public function realEscape(array|string $item): Generator {

            $pureItem = is_array($item) ? $item : [$item];

            foreach ($pureItem as $key => $value) {

                $strValue = (string)$value;
                $escItem = ($this->dbase instanceof mysqli && !$this->dbase->connect_errno)
                    ? $this->dbase->real_escape_string($strValue)
                    : addslashes($strValue);

                yield $key => $escItem;
            }
        }

        public function read(array|string $item, string $mode = "both", bool $column = false): Generator {

            $query = is_array($item) ? $item : [$item];

            $fetchMode = match ($mode) {
                "num" => defined("MYSQLI_NUM") ? (int)constant("MYSQLI_NUM") : self::FETCH_NUM,
                "assoc" => defined("MYSQLI_ASSOC") ? (int)constant("MYSQLI_ASSOC") : self::FETCH_ASSOC,
                default => defined("MYSQLI_BOTH") ? (int)constant("MYSQLI_BOTH") : self::FETCH_BOTH
            };

            foreach ($query as $key => $value) {

                if (!($this->dbase instanceof mysqli) || $this->dbase->connect_errno) {
                    yield $key => false;
                    continue;
                }

                try {
                    if (is_array($value) && isset($value["sql"])) {
                        $sql = (string)$value["sql"];
                        $params = isset($value["params"]) && is_array($value["params"]) ? $value["params"] : [];
                        $types = isset($value["types"]) ? (string)$value["types"] : null;
                        $result = $this->executePreparedResult($sql, $params, $types);
                    } else {
                        $result = $this->dbase->query((string)$value);
                    }
                } catch (Throwable $e) {
                    $this->lastError = $e->getMessage();
                    error_log("[Partarum][DatabaseMysqli] Read error: " . $this->lastError);
                    $result = false;
                }

                if (($result instanceof mysqli_result) && ($column === false)) {

                    if ($result->num_rows !== 0) {

                        $setResult = ($result->num_rows > 1) ? [] : "";

                        for ($i = 0; $i < $result->num_rows; $i++) {

                            if (is_array($setResult)) {
                                $setResult[] = $result->fetch_array($fetchMode);
                            } else {
                                $setResult = $result->fetch_array($fetchMode);
                            }
                        }

                    } else {
                        $setResult = false;
                    }

                    $result->close();

                    yield $key => $setResult;

                } elseif (($result instanceof mysqli_result) && ($column === true)) {

                    $row = $result->fetch_row();
                    $result->close();
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

            if (!($this->dbase instanceof mysqli) || $this->dbase->connect_errno) {
                return false;
            }

            $fetchMode = match ($mode) {
                "num" => defined("MYSQLI_NUM") ? (int)constant("MYSQLI_NUM") : self::FETCH_NUM,
                "both" => defined("MYSQLI_BOTH") ? (int)constant("MYSQLI_BOTH") : self::FETCH_BOTH,
                default => defined("MYSQLI_ASSOC") ? (int)constant("MYSQLI_ASSOC") : self::FETCH_ASSOC
            };

            try {
                $result = $this->executePreparedResult($query, $valueArray, $types);

                if (!($result instanceof mysqli_result)) {
                    return false;
                }

                $rows = $result->fetch_all($fetchMode);
                $result->close();

                return $rows;
            } catch (Throwable $e) {
                $this->lastError = $e->getMessage();
                error_log("[Partarum][DatabaseMysqli] readWithParams error: " . $this->lastError);
                return false;
            }
        }

        public function write(string $item, array $valueArray = [], ?string $types = null): bool {

            if ($valueArray !== []) {
                return $this->writeWithParams($item, $valueArray, $types);
            }

            if (!($this->dbase instanceof mysqli) || $this->dbase->connect_errno) {
                return false;
            }

            try {
                if ($this->dbase->query($item) === true) {
                    return true;
                }

                $this->lastError = $this->dbase->error;
                error_log("[Partarum][DatabaseMysqli] Write error: " . $this->lastError);
                return false;
            } catch (Throwable $e) {
                $this->lastError = $e->getMessage();
                error_log("[Partarum][DatabaseMysqli] Exception during write(): " . $this->lastError);
                return false;
            }
        }

        public function writeWithParams(string $query, array $valueArray, ?string $types = null): bool {

            if (!($this->dbase instanceof mysqli) || $this->dbase->connect_errno) {
                return false;
            }

            try {
                $stmt = $this->dbase->prepare($query);

                if ($stmt === false) {
                    $this->lastError = $this->dbase->error;
                    error_log("[Partarum][DatabaseMysqli] Prepare error: " . $this->lastError);
                    return false;
                }

                if ($valueArray !== []) {
                    $bindTypes = ($types !== null && $types !== "") ? $types : $this->inferBindTypes($valueArray);
                    $values = array_values($valueArray);
                    $stmt->bind_param($bindTypes, ...$values);
                }

                $executed = $stmt->execute();
                $result = $executed && ($stmt->sqlstate === "00000");

                if (!$result) {
                    $this->lastError = $stmt->error;
                }

                $stmt->close();

                return $result;
            } catch (Throwable $e) {
                $this->lastError = $e->getMessage();
                error_log("[Partarum][DatabaseMysqli] writeWithParams error: " . $this->lastError);
                return false;
            }
        }

        public function close(): bool
        {
            if ($this->dbase instanceof mysqli && !$this->dbase->connect_errno) {
                $closed = $this->dbase->close();
                $this->dbase = null;
                return $closed;
            }

            $this->dbase = null;
            return true;
        }

        private function executePreparedResult(string $query, array $valueArray = [], ?string $types = null): mysqli_result|false
        {
            if (!($this->dbase instanceof mysqli) || $this->dbase->connect_errno) {
                return false;
            }

            $stmt = $this->dbase->prepare($query);

            if ($stmt === false) {
                $this->lastError = $this->dbase->error;
                return false;
            }

            if ($valueArray !== []) {
                $bindTypes = ($types !== null && $types !== "") ? $types : $this->inferBindTypes($valueArray);
                $values = array_values($valueArray);
                $stmt->bind_param($bindTypes, ...$values);
            }

            if (!$stmt->execute()) {
                $this->lastError = $stmt->error;
                $stmt->close();
                return false;
            }

            $result = $stmt->get_result();
            $stmt->close();

            return $result;
        }

        private function inferBindTypes(array $values): string
        {
            $types = "";
            foreach ($values as $val) {
                if (is_int($val) || is_bool($val)) {
                    $types .= "i";
                } elseif (is_float($val)) {
                    $types .= "d";
                } else {
                    $types .= "s";
                }
            }

            return $types;
        }
    }
}
