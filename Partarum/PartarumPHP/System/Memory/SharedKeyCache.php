<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\System\Memory {

    use RuntimeException;
    use Shmop;

    class SharedKeyCache extends SharedFactory implements SharedCacheInterface {

        private static ?Shmop $ownCacheInstance = null;

        private static ?array $ownCache = null;

        private static function init(): bool {

            if (!isset(self::$ownCacheInstance, self::$ownCache)) {

                $cache = parent::getOwnCache(self::$ownCacheInstance, SharedCatalog::KEYCACHE_KEY, SharedCatalog::KEYCACHE_SIZE);

                if (is_array($cache)) {
                    self::$ownCacheInstance = $cache["instance"];
                    self::$ownCache = $cache["cache"];
                } else {
                    return $cache;
                }
            }

            return true;
        }

        public static function add(mixed $data): bool {

            if (self::init() && self::$ownCacheInstance !== null) {
                try {
                    self::$ownCache[] = $data;
                    $json = SharedHelper::dataToJSON(self::$ownCache);

                    if ($json === null) {
                        return false;
                    }

                    $process = shmop_write(self::$ownCacheInstance, $json, 0);

                    return (bool)$process;
                } catch (RuntimeException) {
                    return false;
                }
            }

            return false;
        }

        public static function get(mixed $data): mixed {

            if (self::init() && is_array(self::$ownCache)) {
                return self::$ownCache[$data] ?? null;
            }

            return null;
        }

        public static function has(mixed $data): bool {

            if (self::init()) {
                return isset(self::$ownCache) && in_array($data, self::$ownCache, true);
            }

            return false;
        }
    }
}