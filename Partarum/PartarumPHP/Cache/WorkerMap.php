<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Cache {

    class WorkerMap {

        private array $workers = [];

        public function setWorker(string $name, string $value): object {

            $worker = new $value();
            $this->workers[$name] = $worker;

            return $worker;
        }

        public function getWorker(string $name): ?object {

            return $this->workers[$name] ?? null;
        }

        public function isWorker(string $name): bool {

            return isset($this->workers[$name]);
        }

        public function __set(string $name, mixed $value): void {

            if (is_object($value)) {
                $this->workers[$name] = $value;
            }
        }

        public function __get(string $name): ?object {

            return $this->workers[$name] ?? null;
        }

        public function __isset(string $name): bool {

            return isset($this->workers[$name]);
        }
    }
}