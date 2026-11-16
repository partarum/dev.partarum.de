<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\System\Memory {

    use JsonException;

    class SharedHelper {

        public static function dataToJSON(mixed $data): ?string {

            try {
                return json_encode($data, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                return null;
            }
        }

        public static function jsonToArray(string $shmopString): ?array {

            try {
                $cleaned = preg_replace('/[\x00-\x1F\x80-\x9F]/u', '', trim($shmopString));
                if (!is_string($cleaned) || $cleaned === "") {
                    return null;
                }

                $decoded = json_decode($cleaned, true, 512, JSON_THROW_ON_ERROR);
                return is_array($decoded) ? $decoded : null;
            } catch (JsonException) {
                return null;
            }
        }

        public static function createHexString(int $num): string {

            return "0x" . str_pad(dechex($num), 8, "0", STR_PAD_LEFT);
        }
    }
}