<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Security\Token {

    class JWTSignature {

        private string $header = "";

        private string $payload = "";

        private string $secretKey = "";


        public function __construct() {

        }

        public function setSecretKey(string $key): void {

            $this->secretKey = $key;
        }

        public function setHeader(string $header): void {

            $this->header = $header;
        }

        public function setPayload(string $payload): void {

            $this->payload = $payload;
        }

        public function getHash(): string {

            return hash_hmac('sha256', $this->header . "." . $this->payload, $this->secretKey, true);
        }
    }
}