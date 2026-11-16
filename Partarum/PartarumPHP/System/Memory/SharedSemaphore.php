<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\System\Memory {

    use SysvSemaphore;

    class SharedSemaphore {

        private static array $semKeyCache = [];

        private static array $semIDCache = [];

        private static array $semCache = [];

        public static function attach(int $key): bool | SysvSemaphore {

            if (isset(self::$semCache[$key]) && self::$semCache[$key] instanceof SysvSemaphore) {
                return self::$semCache[$key];
            }

            if (!function_exists("sem_get")) {
                return false;
            }

            $sem = @sem_get($key);
            if ($sem instanceof SysvSemaphore) {
                self::$semKeyCache[$key] = $key;
                self::$semCache[$key] = $sem;
                return $sem;
            }

            return false;
        }
    }
}