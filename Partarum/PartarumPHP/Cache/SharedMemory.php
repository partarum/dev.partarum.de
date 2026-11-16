<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Cache {

    use Partarum\PartarumPHP\System\Memory\SharedHelper;
    use Partarum\PartarumPHP\System\Memory\SharedManager;
    use Shmop;

    class SharedMemory {

        private mixed $id = null;

        private int $key;

        private ?Shmop $memory = null;

        public function __construct(int | string | null $key = null) {

            $attach = SharedManager::attach($key);

            $this->memory = $attach["memory"] instanceof Shmop ? $attach["memory"] : null;
            $this->key = (int)$attach["key"];
        }

        public function open(): void {
        }

        public function close(): void {
        }

        public function read(int $offset = 0, int $size = 0): ?array {

            if ($this->memory === null) {
                return null;
            }

            $raw = shmop_read($this->memory, $offset, $size);
            return is_string($raw) ? SharedHelper::jsonToArray($raw) : null;
        }

        public function write(mixed $data): null | bool | int {

            if ($this->memory === null) {
                return null;
            }

            $json = SharedHelper::dataToJSON($data);

            if ($json !== null) {
                return shmop_write($this->memory, $json, 0);
            }

            return null;
        }

        public function add(): void {
        }

        public function get(): void {
        }

        public function remove(): void {

            if ($this->memory !== null) {
                SharedManager::remove($this->memory, $this->key, $this->id);
                $this->memory = null;
            }
        }

        public function __destruct() {
        }
    }
}