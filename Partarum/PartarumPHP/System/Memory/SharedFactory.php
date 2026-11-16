<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\System\Memory {

    use Shmop;

    class SharedFactory {

        protected static function getOwnCache(?Shmop $ownCacheInstance, int $key, int $size): array | bool {

            if ($ownCacheInstance !== null) {
                return true;
            }

            $ownInstance = self::getOwnCacheInstance($key, $size);

            if ($ownInstance === null) {
                return false;
            }

            $raw = shmop_read($ownInstance, 0, $size);
            $ownCache = is_string($raw) ? (SharedHelper::jsonToArray($raw) ?? []) : [];

            return ["instance" => $ownInstance, "cache" => $ownCache];
        }

        private static function getOwnCacheInstance(int $key, int $size): ?Shmop {

            return self::getCacheInstance($key, $size) ?? self::createCacheInstance($key, $size);
        }

        protected static function createCacheInstance(int $key, int $size = 1024): ?Shmop {

            if (!function_exists("shmop_open")) {
                return null;
            }

            $cacheInstance = @shmop_open($key, "c", 0644, $size);

            return ($cacheInstance !== false) ? $cacheInstance : null;
        }

        protected static function getCacheInstance(int $key, int $size = 1024): ?Shmop {

            if (!function_exists("shmop_open")) {
                return null;
            }

            $cacheInstance = false;

            if (@shmop_open($key, "a", 0, 0) !== false) {
                $cacheInstance = @shmop_open($key, "w", 0, 0);
            }

            return ($cacheInstance !== false) ? $cacheInstance : null;
        }
    }
}