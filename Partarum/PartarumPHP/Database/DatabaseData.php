<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Database {

    use JsonException;
    use RuntimeException;

    class DatabaseData {

        private DatabaseDataObject $data;

        private ?object $dbJSON = null;

        public function __construct(?string $configFile = null) {

            $this->data = new DatabaseDataObject();
            $this->loadFromEnv();

            $file = $configFile ?? $this->resolveConfigFile();

            if (is_file($file)) {

                try {

                    $dbJSONString = file_get_contents($file);

                    if ($dbJSONString !== false && trim($dbJSONString) !== "") {
                        try {

                            $decoded = json_decode($dbJSONString, false, 512, JSON_THROW_ON_ERROR);

                            if (is_object($decoded)) {
                                $this->dbJSON = $decoded;
                                $this->setData();
                            }

                        } catch (JsonException $je) {
                            error_log("[Partarum][DatabaseData] JSON decode error in {$file}: " . $je->getMessage());
                        }
                    }

                } catch (RuntimeException $re) {
                    error_log("[Partarum][DatabaseData] Runtime error reading {$file}: " . $re->getMessage());
                }
            }
        }

        private function resolveConfigFile(): string {

            if (defined("ROOT")) {
                return rtrim((string)ROOT, "/") . "/Partarum/PartarumLorem/.DB.json";
            }

            if (!empty($_SERVER["DOCUMENT_ROOT"])) {
                return rtrim((string)$_SERVER["DOCUMENT_ROOT"], "/") . "/Partarum/PartarumLorem/.DB.json";
            }

            return dirname(__DIR__, 2) . "/PartarumLorem/.DB.json";
        }

        private function loadFromEnv(): void {

            if (!empty($_ENV["DB_TYPE"])) {
                $this->data->setDatabaseType((string)$_ENV["DB_TYPE"]);
            }

            if (!empty($_ENV["DB_NAME"])) {
                $this->data->setDatabaseName((string)$_ENV["DB_NAME"]);
            }

            if (!empty($_ENV["DB_HOST"])) {
                $this->data->setHost((string)$_ENV["DB_HOST"]);
            }

            if (!empty($_ENV["DB_USER"])) {
                $this->data->setUsername((string)$_ENV["DB_USER"]);
            }

            if (isset($_ENV["DB_PASS"])) {
                $this->data->setPassword((string)$_ENV["DB_PASS"]);
            }
        }

        private function setData(): void {

            if ($this->dbJSON === null) {
                return;
            }

            foreach (get_object_vars($this->dbJSON) as $key => $value) {

                $strValue = (string)$value;

                switch ($key) {

                    case "DatabaseType":
                    case "DatabaseTyp":

                        if ($strValue !== "") {
                            $this->data->setDatabaseType($strValue);
                        }
                        break;

                    case "DatabaseName":

                        if ($strValue !== "") {
                            $this->data->setDatabaseName($strValue);
                        }
                        break;

                    case "Host":

                        if ($strValue !== "") {
                            $this->data->setHost($strValue);
                        }
                        break;

                    case "Username":

                        if ($strValue !== "") {
                            $this->data->setUsername($strValue);
                        }
                        break;

                    case "Password":

                        if ($strValue !== "") {
                            $this->data->setPassword($strValue);
                        }
                        break;
                }
            }
        }

        public function getData(): DatabaseDataObject {

            return $this->data;
        }
    }
}