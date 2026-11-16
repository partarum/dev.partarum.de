<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\System\Memory {

    use JsonException;
    use Partarum\PartarumPHP\System\Root;
    use Shmop;

    class SharedManager extends SharedFactory {

        private static array $shmodCache = [];

        private static ?string $root = null;

        /**
         * Checks if there is an shmop instance with the key in the Cache
         *
         * @param string|int|null $key The Key for the Shmop
         * @param int|null        $size The Size for the RAM
         *
         * @return array An Array with the instance and the key
         */
        public static function attach(null | string | int $key = null, null | int $size = 1024): array {

            if (self::$root === null) {
                self::$root = Root::getPath();
            }

            $inode = self::createKey($key);
            $segmentSize = $size ?? 1024;

            if ($inode > 0 && !SharedKeyCache::has($inode)) {
                SharedKeyCache::add($inode);
            }

            if ($inode > 0 && !isset(self::$shmodCache[$inode]) && function_exists("shmop_open")) {
                $opened = @shmop_open($inode, "c", 0644, $segmentSize);
                self::$shmodCache[$inode] = ($opened !== false) ? $opened : null;
            }

            return ["key" => $inode, "memory" => self::$shmodCache[$inode] ?? null];
        }

        public static function detach(): void {
        }

        public static function remove(?Shmop $instance, int $key, mixed $id = null): bool {

            unset(self::$shmodCache[$key]);

            if ($instance !== null && function_exists("shmop_delete")) {
                return @shmop_delete($instance);
            }

            return false;
        }

        private static function getKeyFromString(?string $string = null): int {

            if ($string !== null) {
                return file_exists($string) ? (int)(fileinode($string) ?: 0) : 0;
            }

            $file = self::createFileForInode();

            return is_array($file) ? (int)($file["inode"] ?? 0) : 0;
        }

        private static function createKey(null | string | int $key): int {

            return match (gettype($key)) {
                "string" => self::getKeyFromString($key),
                "integer" => $key,
                "NULL" => self::getKeyFromString(),
                default => 0
            };
        }

        private static function createFileForInode(): ?array {

            $timestamp = time();
            $timeString = (string)$timestamp;
            $date = getdate($timestamp);
            $fileName = date("Y_m_d", $timestamp) . "_" . $timeString;

            try {
                $json = json_encode($date, JSON_THROW_ON_ERROR);

                $baseRoot = defined("ROOT") ? (string)ROOT : (self::$root ?? Root::getPath());
                $slash = defined("SLASH") ? (string)SLASH : DIRECTORY_SEPARATOR;
                $dirPath = rtrim($baseRoot, "/\\") . $slash . "Partarum" . $slash . "PartarumLorem" . $slash . "SharedMemory";

                if (!is_dir($dirPath) && !@mkdir($dirPath, 0775, true) && !is_dir($dirPath)) {
                    return null;
                }

                $path = $dirPath . $slash . $fileName . ".json";

                file_put_contents($path, $json);

                $inode = (int)(fileinode($path) ?: 0);

                $smPath = $dirPath . $slash . ".SM.json";
                $smJSONArray = [];

                if (is_file($smPath)) {
                    $smJSON = file_get_contents($smPath);
                    if (is_string($smJSON) && trim($smJSON) !== "") {
                        $decoded = json_decode($smJSON, true, 512, JSON_THROW_ON_ERROR);
                        if (is_array($decoded)) {
                            $smJSONArray = $decoded;
                        }
                    }
                }

                $smJSONArray[$fileName] = $inode;

                file_put_contents($smPath, json_encode($smJSONArray, JSON_THROW_ON_ERROR));

                return ["file" => $path, "inode" => $inode];
            } catch (JsonException) {
                return null;
            }
        }
    }
}