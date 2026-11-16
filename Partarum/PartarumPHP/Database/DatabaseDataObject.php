<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Database {

    class DatabaseDataObject {

        private string $databaseType = "MySQL";

        private string $databaseName = "";

        private string $host = "localhost";

        private string $username = "";

        private string $password = "";

        public function __construct() {

        }

        /**
         * @return string
         */
        public function getDatabaseType(): string {

            return $this->databaseType !== "" ? $this->databaseType : "MySQL";
        }

        /**
         * @param string $databaseType
         */
        public function setDatabaseType(string $databaseType): void {

            $this->databaseType = $databaseType;
        }

        /**
         * @return string
         */
        public function getDatabaseName(): string {

            return $this->databaseName;
        }

        /**
         * @param string $databaseName
         */
        public function setDatabaseName(string $databaseName): void {

            $this->databaseName = $databaseName;
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

    }
}