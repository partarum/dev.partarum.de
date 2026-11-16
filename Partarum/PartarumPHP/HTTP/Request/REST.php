<?php
declare(strict_types=1);
/*
 *           Copyright 2020 © Alexander Bombis. All rights reserved.
 *           Developed by Alexander Bombis.
 *           Email: email@alexander-bombis.de
 *
 *           The following code was created based on the template for the website http://cordes-software.de.
 *           This may also be used in full by the person or business
 *           representing the domain "cordes-software.de"
 *           and also modified for their use.
 */

namespace Partarum\PartarumPHP\HTTP\Request {

    use JsonException;
    use stdClass;

    /**
     * Class REST
     *
     * @package Partarum\HTTP\Request
     */
    class REST {

        public const POST = CURLOPT_POST;

        public const POST_FIELDS = CURLOPT_POSTFIELDS;

        public const GET = CURLOPT_HTTPGET;

        public const AUTH = CURLOPT_HTTPAUTH;

        public const HEADER = CURLOPT_HTTPHEADER;

        public const URL = CURLOPT_URL;

        public const JSON = "Content-Type: application/json";

        /**
         * @var string
         */
        public static string $post = "php://input";

        private $curl;

        private $response;

        private array $header = [];

        /**
         * @var string[]
         */
        private array $headerFunktions = [
            "auth" => "setAuth",
            "contentType" => "setContentType"
        ];

        /**
         * @var object|stdClass
         */
        private object $cache;

        public mixed $info;

        /**
         * REST constructor.
         */
        public function __construct(){

            $this->curl = curl_init();

            $this->cache = new stdClass();
        }

        /**
         * @param string $url
         */
        public function setURL(string $url): void {

            curl_setopt($this->curl, self::URL, $url);
        }

        /**
         * @param string $method
         */
        public function setMethod(string $method): void {

            $this->cache->method = match($method){
                "GET" => REST::GET,
                "POST" => REST::POST
            };

            curl_setopt($this->curl, $this->cache->method, 1);
        }

        /**
         * @param array|null $header
         */
        public function setHeader(?array $header): void {

            foreach($header as $key => $value){

                $part = $this->{$this->headerFunktions[$key]}($value);

                if($part !== NULL){
                    $this->header[] = $part;
                }
            }

            curl_setopt($this->curl, self::HEADER, $this->header);
        }

        /**
         * @param array|null $auth
         *
         * @return string|null
         */
        public function setAuth(?array $auth): ?string {

            $tokenString = "";

            if($auth !== NULL) {

                foreach ($auth as $key => $value) {
                    $tokenString = $key . '="' . $value . '"';
                }
            }

            return ($auth !== NULL) ? 'Authorization: Token '.$tokenString : NULL;
        }

        /**
         * @param string $type
         *
         * @return string
         */
        public function setContentType(string $type): string {

            if($type === self::JSON){

                $this->cache->contentType = $type;

                $this->setReturnTransfer();
            }

            return $type;
        }

        /**
         *
         */
        public function setReturnTransfer(): void {

            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
        }

        /**
         * @param mixed $content
         *
         * @throws JsonException
         */
        public function setContent(mixed $content): void {

            if(isset($this->cache->contentType)){

                if($this->cache->contentType === self::JSON){

                    try {

                        $data = json_encode($content, JSON_THROW_ON_ERROR);

                        $this->setContentType(self::JSON);

                    } catch (JsonException $e) {

                        error_log("REST::setContent JsonException: " . $e->getMessage());
                        $data = json_encode([$e->getMessage()], JSON_THROW_ON_ERROR);
                    }

                    if(isset($this->cache->method)) {

                        if($this->cache->method === self::POST) {

                            curl_setopt($this->curl, self::POST_FIELDS, $data);
                        }
                    }
                }
            }
        }

        /**
         * @return bool|string
         */
        public function exec(): bool|string {

            $this->setReturnTransfer(); // ! muss ggf. noch abgeändert werden

            $this->response = curl_exec($this->curl);

            return $this->response;
        }

        /**
         *
         */
        public function close(): void {

            $this->info = curl_getinfo($this->curl);

            if(curl_errno($this->curl)) {
                error_log("REST::close cURL error (" . curl_errno($this->curl) . "): " . curl_error($this->curl));
            }

            curl_close($this->curl);
        }

        /**
         * @return REST
         */
        public static function create(): REST {

            return new REST();
        }


        /**
         * @param string $string
         *
         * @return string
         */
        public static function sha256(string $string): string {
            return hash("sha256", $string);
        }

        /**
         * @param string $string
         *
         * @return string
         */
        public static function sha512(string $string): string {
            return hash("sha512", $string);
        }

        /**
         * @param mixed $request
         *
         * @return false|mixed
         */
        public static function convertData(mixed $request): mixed {

            if(!is_string($request) || $request === "") {
                return false;
            }

            try {
                return json_decode($request, false, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                error_log("REST::convertData JsonException: " . $e->getMessage());
                return false;
            }

        }

        /**
         * @return false|mixed
         */
        public static function getRequestData() : mixed {

            return self::convertData(file_get_contents(self::$post));
        }

    }
}
