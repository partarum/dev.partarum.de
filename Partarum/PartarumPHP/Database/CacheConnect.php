<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Database {

    use Generator;
    use Partarum\PartarumPHP\Security\SessionWorker;
    use Partarum\PartarumPHP\Service\Binary\FlagWorker;

    class CacheConnect extends FlagWorker {

        public const SESSION = 2;

        public const DATABASE = 4;

        public bool $connect = false;

        public DatabaseMysqli|DatabasePDO|null $db = null;

        private ?DatabaseDataObject $databaseData = null;

        public function __construct(int $item = CacheConnect::SESSION | CacheConnect::DATABASE) {
            parent::__construct();

            $this->setFlags($item, true);

            if ($this->isFlagSet(self::SESSION)) {
                $ses = new SessionWorker(false);
                if ($ses->sessionOpen === true) {
                    $this->connect = true;
                }
            }
        }

        public function openDB(?array $data = null): DatabaseMysqli|DatabasePDO {

            $this->getData();

            $type = strtolower($this->databaseData->getDatabaseType());

            switch ($type) {

                case "mysql":
                case "mysqli":

                    $db = new DatabaseMysqli();

                    $db->setHost($this->databaseData->getHost());
                    $db->setDatabase($this->databaseData->getDatabaseName());
                    $db->setUsername($this->databaseData->getUsername());
                    $db->setPassword($this->databaseData->getPassword());

                    $this->connect = isset($data) ? $db->open($data) : $db->open();

                    break;

                default:

                    $db = new DatabasePDO();

                    $db->setDatabaseType($this->databaseData->getDatabaseType());
                    $db->setHost($this->databaseData->getHost());
                    $db->setDatabase($this->databaseData->getDatabaseName());
                    $db->setUsername($this->databaseData->getUsername());
                    $db->setPassword($this->databaseData->getPassword());

                    $this->connect = isset($data) ? $db->open($data) : $db->open();
                    break;
            }

            $this->db = $db;

            return $db;
        }

        public function read(array|string $item, string $mode = "both", bool $column = false): Generator {

            if ($this->db === null) {
                $this->openDB();
            }

            return $this->db->read($item, $mode, $column);
        }

        public function readWithParams(
            string $query,
            array $valueArray = [],
            ?string $types = null,
            string $mode = "assoc"
        ): array|false {

            if ($this->db === null) {
                $this->openDB();
            }

            return $this->db->readWithParams($query, $valueArray, $types, $mode);
        }

        public function write(string $item, array $valueArray = [], ?string $types = null): bool {

            if ($this->db === null) {
                $this->openDB();
            }

            return $this->db->write($item, $valueArray, $types);
        }

        public function writeWithParams(string $query, array $valueArray, ?string $types = null): bool {

            if ($this->db === null) {
                $this->openDB();
            }

            return $this->db->writeWithParams($query, $valueArray, $types);
        }

        public function close(): bool {

            if ($this->db !== null) {
                $closed = $this->db->close();
                $this->db = null;
                $this->connect = false;
                return $closed;
            }

            return true;
        }

        public function getDatabaseData(): DatabaseDataObject {

            if ($this->databaseData === null) {
                $this->getData();
            }

            return $this->databaseData;
        }

        private function getData(): void {

            $data = new DatabaseData();

            $this->databaseData = $data->getData();
        }
    }
}
