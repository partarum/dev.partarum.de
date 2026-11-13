<?php
namespace Partarum\PartarumPHP\Security\Token {

    use Exception;

    class JWTPayload {

        const ALGO = "aes-256-cbc";

        const BYTE_LENGTH = 16;

        private array $cache = [];

        /**
         * @throws Exception
         */
        private function encryptValue(string $value) : bool|string {

            $secretKey = $_ENV["JWT_CRYPTO_SECRET_KEY"] || "partarumSecret";

            $bytes = random_bytes(self::BYTE_LENGTH) ?? openssl_random_pseudo_bytes(16);

            return openssl_encrypt($value, self::ALGO, $secretKey, OPENSSL_RAW_DATA, $bytes);
        }

        /**
         * @throws Exception
         */
        private function decryptValue(string $string): bool|string {

            $secretKey = $_ENV["JWT_CRYPTO_SECRET_KEY"] || "partarumSecret";

            $bytes = random_bytes(16) ?? openssl_random_pseudo_bytes(16);

            return openssl_decrypt($string, self::ALGO, $secretKey,OPENSSL_RAW_DATA, $bytes);
        }

        /**
         * @throws Exception
         */
        public function __set($name, $value) {

            if(!isset($this->cache[$name])) {

                $this->cache[$name] = base64_encode($this->encryptValue($value));
            }
        }

        /**
         * @throws Exception
         */
        public function __get($name) {

            return $this->decryptValue(($this->cache[$name]));
        }

        public function getAll(): array {

            return $this->cache;
        }

        public function __isset($name) {

            return isset($this->$name);
        }
    }
}