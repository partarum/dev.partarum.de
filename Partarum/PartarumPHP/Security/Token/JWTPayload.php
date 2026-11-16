<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Security\Token {

    use Exception;

    class JWTPayload {

        public const ALGO = "aes-256-cbc";

        public const BYTE_LENGTH = 16;

        private array $cache = [];

        /**
         * @throws Exception
         */
        private function encryptValue(string $value) : bool|string {

            $secretKey = $_ENV["JWT_CRYPTO_SECRET_KEY"] ?? "partarumSecret";

            $iv = random_bytes(self::BYTE_LENGTH);

            $cipher = openssl_encrypt($value, self::ALGO, $secretKey, OPENSSL_RAW_DATA, $iv);

            return ($cipher !== false) ? $iv . $cipher : false;
        }

        /**
         * @throws Exception
         */
        private function decryptValue(string $string): bool|string {

            $secretKey = $_ENV["JWT_CRYPTO_SECRET_KEY"] ?? "partarumSecret";

            if(strlen($string) <= self::BYTE_LENGTH) {
                return false;
            }

            $iv = substr($string, 0, self::BYTE_LENGTH);
            $cipher = substr($string, self::BYTE_LENGTH);

            return openssl_decrypt($cipher, self::ALGO, $secretKey, OPENSSL_RAW_DATA, $iv);
        }

        /**
         * @throws Exception
         */
        public function __set(int | string $name, mixed $value): void {

            if(!isset($this->cache[$name])) {

                $encrypted = $this->encryptValue((string)$value);

                if($encrypted !== false) {
                    $this->cache[$name] = base64_encode($encrypted);
                }
            }
        }

        /**
         * @throws Exception
         */
        public function __get(int | string $name): mixed {

            if(!isset($this->cache[$name])) {
                return null;
            }

            $decoded = base64_decode((string)$this->cache[$name], true);

            return ($decoded !== false) ? $this->decryptValue($decoded) : false;
        }

        public function getAll(): array {

            return $this->cache;
        }

        public function __isset(int | string $name): bool {

            return isset($this->cache[$name]);
        }
    }
}