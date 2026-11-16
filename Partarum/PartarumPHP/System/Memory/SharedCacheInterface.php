<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\System\Memory {

    interface SharedCacheInterface {

        public static function add(mixed $data): bool;

        public static function get(mixed $data): mixed;

        public static function has(mixed $data): bool;
    }
}