<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Cache;

use Exception;
use Predis\Client;
use Predis\Response\Status;

class RedisCache {

    /**
     * Erstellt und verbindet einen Predis\Client anhand der Partarum-ENV-Konfiguration.
     *
     * @param array $config
     * @return Client
     * @throws Exception
     */
    public static function connect(array $config): Client {

        try {
            return new Client([
                "host" => $config["REDIS_IP"] ?? "127.0.0.1",
                "port" => $config["REDIS_PORT"] ?? 6379,
                "database" => $config["REDIS_DB"] ?? 0,
                "username" => $config["REDIS_USER"] ?? null,
                "password" => $config["REDIS_PASS"] ?? null
            ]);
        } catch(Exception $ex) {
            error_log("RedisCache::connect Exception: " . $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * Speichert einen Wert unter <idKey>:cache:<key> in Redis.
     *
     * @param Client $redis
     * @param string $idKey
     * @param string $key
     * @param string|int $value
     * @param int $expires
     * @return Status
     */
    public static function set(Client $redis, string $idKey, string $key, string | int $value, int $expires = 0): Status {

        $cacheKey = $idKey . ":cache:" . $key;

        return match($expires) {
            0 => $redis->set($cacheKey, $value),
            default => $redis->set($cacheKey, $value, "EX", $expires)
        };
    }

    /**
     * Liest einen Wert unter <idKey>:cache:<key> aus Redis.
     *
     * @param Client $redis
     * @param string $idKey
     * @param string $key
     * @return string|null
     */
    public static function get(Client $redis, string $idKey, string $key): ?string {

        return $redis->get($idKey . ":cache:" . $key);
    }

    /**
     * Prüft, ob ein Key unter <idKey>:cache:<key> in Redis existiert.
     *
     * @param Client $redis
     * @param string $idKey
     * @param string $key
     * @return bool
     */
    public static function has(Client $redis, string $idKey, string $key): bool {

        return (bool)$redis->exists($idKey . ":cache:" . $key);
    }
}
