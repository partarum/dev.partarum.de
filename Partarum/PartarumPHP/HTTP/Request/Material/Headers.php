<?php
declare(strict_types=1);
/*
 *   Copyright 2018- 2021 © Alexander Bombis. All rights reserved.
 *            Developed by Alexander Bombis.
 *            Email: email@alexander-bombis.de
 */
namespace Partarum\PartarumPHP\HTTP\Request\Material {

    class Headers {


        public array $headers = [
            "css" => [
                "content" => "text/css"
            ],
            "js" => [
                "content" => "application/javascript"
            ],
            "html" => [
                "content" => "text/html"
            ],
            "txt" => [
                "content" => "text/plain"
            ],
            "png" => [
                "content" => "image/png"
            ],
            "jpg" => [
                "content" => "image/jpeg"
            ],
            "jpeg" => [
                "content" => "image/jpeg"
            ],
            "webp" => [
                "content" => "image/webp"
            ],
            "svg" => [
                "content" => "image/svg+xml"
            ],
            "ico" => [
                "content" => "image/x-icon"
            ],
            "woff" => [
                "content" => "font/woff"
            ],
            "woff2" => [
                "content" => "font/woff2"
            ],
            "zip" => [
                "content" => "application/zip"
            ]
        ];

        public string $ext;

        public const CONTENT = "Content-Type: ";

        public const CACHE_CONTROL = "Cache-Control: ";

        public function __construct(string $ext){

            $this->ext = strtolower($ext);

            $this->setContent();

        }

        public static function setHeader(string $ext): Headers {

            return (new Headers($ext));
        }

        protected function setContent(): void {

            if($this->ext !== '' && !headers_sent()) {

                if(array_key_exists($this->ext, $this->headers)) {

                    header(self::CONTENT . $this->headers[$this->ext]["content"]);
                }
            }
        }

        public function setCacheControl(string $type, null | int $age): void {

            if(headers_sent()) {
                return;
            }

            if($age === null) {

                header(self::CACHE_CONTROL . $type);

            } else {

                header(self::CACHE_CONTROL . $type . ", max-age={$age}");
            }
        }

        public function setContentDisposition(string $realURL): void {

            if(headers_sent()) {
                return;
            }

            header('Content-Disposition: inline; filename="'.basename($realURL).'"');
        }
    }
}

