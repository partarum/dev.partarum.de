<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Security\Token {

    use Generator;
    use JsonException;

    class JWT {

        public JWTObject $object;

        /*
         *  HS256 for secret key
         *  RS256 for privat 
         */
        private ?string $hash = null;


        public function __construct() {

            $this->object = new JWTObject();

            $this->object->setHeader("HS256", "sha256");

        }

        public function setPayload(mixed $payload): void {

            $this->object->setPayload($payload);
        }

        public function getPayload(): JWTPayload {

            return $this->object->payload;
        }

        public function getToken(): string {

            $this->hash = $this->object->setSignature();

            $tokenGenerator = $this->encode([$this->object->getHeader(), $this->object->getPayload(), $this->hash]);

            $token = $tokenGenerator->current();
            $tokenGenerator->next();
            $token .= "." . $tokenGenerator->current();
            $tokenGenerator->next();
            $token .= "." . $tokenGenerator->current();

            return $token;
        }

        public function setSecretKey(string $key): void {

            $this->object->setSecretKey($key);
        }

        public function encode(array $array): Generator
        {
            foreach ($array as $item) {

                yield str_replace(['+', '/', '='], ['-', '_', ''], base64_encode((string)$item));
            }
        }

        public function decode(string | array $token) : ?array {

            $tokenArray = (is_array($token)) ? explode(".", $token[0]) : explode(".", $token);

            if(count($tokenArray) === 3) {

                $stringArray = [];

                foreach ($tokenArray as $part) {

                    $stringArray[] = base64_decode(str_replace(['-', '_', ''], ['+', '/', '='], $part));
                }

                return $stringArray;
            }

            return NULL;
        }

        public function checkToken(?string $token, string $key) : bool{

            if(isset($token)) {

                $stringArray = $this->decode($token);

                if(isset($stringArray)) {

                    try {

                        $this->object->setPayload(json_decode($stringArray[1], TRUE, 512, JSON_THROW_ON_ERROR));

                        $expectedSignature = hash_hmac('sha256', $stringArray[0] . "." . $stringArray[1], $key, TRUE);

                        return hash_equals($expectedSignature, $stringArray[2]);

                    } catch (JsonException $e) {

                        error_log("JWT::checkToken JsonException: " . $e->getMessage());
                    }
                }
            }

            return FALSE;
        }
    }
}