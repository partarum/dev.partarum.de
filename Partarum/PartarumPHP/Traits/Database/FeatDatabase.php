<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Traits\Database {

    use Generator;

    trait FeatDatabase {

        public function getTableMission(array|string $item, ?string $mission = "base"): string
        {
            $tableName = "";
            $setSelect = "*";
            $sqlMission = "";

            if (($mission !== null) && ($mission !== "self") && is_array($item)) {

                if (isset($item["table"])) {
                    $tableName = $this->sanitizeIdentifier((string)$item["table"]);
                }

                if (isset($item["select"])) {
                    $setSelect = (string)$item["select"];
                }
            }

            switch ($mission) {
                case "base":
                    $sqlMission = "SELECT {$setSelect} FROM {$tableName}";
                    break;

                case "self":
                    $sqlMission = is_string($item) ? $item : "";
                    break;
            }

            return $sqlMission;
        }

        public function mysqlBase(): ?string {

            if (!isset($this->dbase) || $this->dbase === null) {
                return null;
            }

            $version = $this->dbase->query("SELECT version() AS version");

            if ($version === false) {
                return null;
            }

            $resultVersion = method_exists($version, "fetch_object")
                ? $version->fetch_object()
                : (method_exists($version, "fetchObject") ? $version->fetchObject() : null);

            if (method_exists($version, "close")) {
                $version->close();
            }

            return json_encode($resultVersion) ?: null;
        }

        public function createUser(array $item): bool
        {
            if (!isset($this->dbase, $item[0], $item[1]) || $this->dbase === null) {
                return false;
            }

            $user = $this->sanitizeIdentifier((string)$item[0]);
            $password = addslashes((string)$item[1]);
            $sql = "GRANT ALL PRIVILEGES ON *.* TO '{$user}'@'localhost' IDENTIFIED BY '{$password}' WITH GRANT OPTION";

            return (bool)$this->dbase->query($sql);
        }

        public function createDatabase(string $item): bool
        {
            if (!isset($this->dbase) || $this->dbase === null) {
                return false;
            }

            $dbName = $this->sanitizeIdentifier($item);
            if ($dbName === "") {
                return false;
            }

            $sql = "CREATE DATABASE IF NOT EXISTS `{$dbName}`";

            return (bool)$this->dbase->query($sql);
        }

        public function showDatabase(bool|string|array $item = true): array|string|false
        {
            /*
             * true == alle Datenbanken, ist immer, auch ohne Angabe
             * false == keine systembezogenen Datenbanken, muss angegeben werden
             * string == suchen nach einer bestimmten Datenbank, muss mitgegeben werden
             * array mit strings == suchen bestimmter Datenbanken, muss mitgegeben werden
             */

            $mysqlSystemDatabases = ["information_schema", "cdcol", "mysql", "performance_schema", "phpmyadmin", "sys"];
            $rawResult = $this->read("SHOW DATABASES");
            $databases = ($rawResult instanceof Generator) ? $rawResult->current() : $rawResult;

            if (!is_array($databases)) {
                return false;
            }

            if (is_array($databases) && isset($databases[0]) && !is_array($databases[0])) {
                $databases = [$databases];
            }

            $allNames = array_map(static fn($x) => is_array($x) ? (string)($x[0] ?? reset($x)) : (string)$x, $databases);

            if ($item === true) {
                return array_values($allNames);
            }

            if ($item === false) {
                return array_values(array_diff($allNames, $mysqlSystemDatabases));
            }

            if (is_string($item)) {
                $filtered = array_values(array_filter($allNames, static fn(string $db): bool => $db === $item));

                return count($filtered) > 0 ? $filtered[0] : false;
            }

            $filtered = array_values(array_filter($allNames, static fn(string $db): bool => in_array($db, $item, true)));

            return $filtered;
        }

        public function getUser(bool|string|array $itemUser = true, bool|string|array $itemRow = true): mixed
        {
            /* only root!!!! */
            $getUser = null;

            if (is_bool($itemUser) && $itemUser === true) {

                if (is_bool($itemRow) && $itemRow === true) {

                    $getUser = $this->read("SELECT * FROM user");

                } elseif (is_bool($itemRow) && $itemRow === false) {

                    $getUser = $this->read("SELECT user FROM user");
                }
            } elseif (is_string($itemUser)) {

                $table = $this->sanitizeIdentifier($itemUser);

                if (is_bool($itemRow) && $itemRow === true) {

                    $getUser = $this->read("SELECT * FROM `{$table}`");

                } elseif (is_bool($itemRow) && $itemRow === false) {

                    $getUser = $this->read($itemUser);

                } elseif (is_string($itemRow)) {

                    $col = $this->sanitizeIdentifier($itemRow);
                    $getUser = $this->read("SELECT `{$col}` FROM `{$table}`");

                } elseif (is_array($itemRow)) {

                    $cols = array_map(fn($c) => "`" . $this->sanitizeIdentifier((string)$c) . "`", $itemRow);
                    $rowString = implode(", ", $cols);
                    $getUser = $this->read("SELECT {$rowString} FROM `{$table}`");
                }
            } elseif (is_array($itemUser)) {

                $tables = array_map(fn($t) => "`" . $this->sanitizeIdentifier((string)$t) . "`", $itemUser);
                $userString = implode(", ", $tables);

                if (is_bool($itemRow) && $itemRow === true) {

                    $getUser = $this->read("SELECT * FROM {$userString}");

                } elseif (is_bool($itemRow) && $itemRow === false) {

                    $getUser = $this->read("SELECT user FROM user");

                } elseif (is_string($itemRow)) {

                    $col = $this->sanitizeIdentifier($itemRow);
                    $getUser = $this->read("SELECT `{$col}` FROM {$userString}");

                } elseif (is_array($itemRow)) {

                    $cols = array_map(fn($c) => "`" . $this->sanitizeIdentifier((string)$c) . "`", $itemRow);
                    $rowString = implode(", ", $cols);
                    $getUser = $this->read("SELECT {$rowString} FROM {$userString}");
                }
            }

            return $getUser;
        }

        public function createTable(array $item = []): string|false
        {
            if (!isset($this->dbase) || $this->dbase === null) {
                return json_encode(["Tabelle konnte nicht angelegt werden!"]);
            }

            $rawName = isset($item["name"]) ? (string)$item["name"] : "anders";
            $tableName = $this->sanitizeIdentifier($rawName);
            if ($tableName === "") {
                $tableName = "anders";
            }

            $sql = "CREATE TABLE IF NOT EXISTS `{$tableName}` (
                        id INT(11) NOT NULL AUTO_INCREMENT,
                        name VARCHAR(50) DEFAULT NULL,
                        bevdichte FLOAT DEFAULT NULL,
                        PRIMARY KEY (id))";

            if ($this->dbase->query($sql)) {
                return json_encode(["Tabelle wurde angelegt"]);
            }

            return json_encode(["Tabelle konnte nicht angelegt werden!"]);
        }

        protected function sanitizeIdentifier(string $identifier): string
        {
            return preg_replace('/[^a-zA-Z0-9_]/', '', $identifier) ?? "";
        }
    }
}