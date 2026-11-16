<?php
declare(strict_types=1);
/*
 *   Copyright 2018- 2021 © Alexander Bombis. All rights reserved.
 *            Developed by Alexander Bombis.
 *            Email: email@alexander-bombis.de
 */
namespace Partarum\PartarumPHP\HTTP\Request\Material {

    use Partarum\PartarumPHP\HTTP\Request\Material\Headers;

    class Extension {

        public const ALLOWED_EXTENSIONS = [
            "js",
            "css",
            "html",
            "txt",
            "php",
            "zip",
            "jpg",
            "jpeg",
            "png",
            "webp",
            "svg",
            "ico",
            "woff",
            "woff2"
        ];

        public Headers $headers;

        public string $realURL;

        public null | array $cacheTime;

        public function __construct(string $realUrl, string $ext, null | array $cacheTime = null){

            $this->realURL = $realUrl;

            $this->cacheTime = $cacheTime;

            $normalizedExt = strtolower($ext);

            if($normalizedExt === "" || !in_array($normalizedExt, self::ALLOWED_EXTENSIONS, true) || !is_file($this->realURL)) {
                http_response_code(404);
                return;
            }

            $this->headers = Headers::setHeader($normalizedExt);

            $this->{$normalizedExt}();
        }

        public static function action(string $realUrl, string $ext, null | array $cacheTime = null): void {

            (new Extension($realUrl, $ext, $cacheTime));
        }

        protected function js(): void {

            $this->headers->setCacheControl("public", $this->cacheTime["js"] ?? null);

            $this->headers->setContentDisposition($this->realURL);

            readfile($this->realURL);
        }

        protected function css(): void {

            $this->headers->setCacheControl("public", $this->cacheTime["css"] ?? null);

            $this->headers->setContentDisposition($this->realURL);

            readfile($this->realURL);
        }

        protected function html(): void {

            readfile($this->realURL);
        }

        protected function txt(): void {

            $this->headers->setContentDisposition($this->realURL);

            readfile($this->realURL);
        }

        protected function php(): void {

            require_once($this->realURL);
        }

        protected function zip(): void {

            $this->headers->setContentDisposition($this->realURL);

            readfile($this->realURL);
        }

        protected function jpg(): void {

            $this->headers->setContentDisposition($this->realURL);

            readfile($this->realURL);
        }

        protected function jpeg(): void {

            $this->jpg();
        }

        protected function png(): void {

            $this->headers->setContentDisposition($this->realURL);

            readfile($this->realURL);
        }

        protected function webp(): void {

            $this->headers->setContentDisposition($this->realURL);

            readfile($this->realURL);
        }

        protected function svg(): void {

            $this->headers->setContentDisposition($this->realURL);

            readfile($this->realURL);
        }

        protected function ico(): void {

            $this->headers->setContentDisposition($this->realURL);

            readfile($this->realURL);
        }

        protected function woff(): void {

            $this->headers->setContentDisposition($this->realURL);

            readfile($this->realURL);
        }

        protected function woff2(): void {

            $this->headers->setContentDisposition($this->realURL);

            readfile($this->realURL);
        }

    }
}