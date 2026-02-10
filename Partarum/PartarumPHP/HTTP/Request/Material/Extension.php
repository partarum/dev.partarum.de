<?php
/*
 *   Copyright 2018- 2021 © Alexander Bombis. All rights reserved.
 *            Developed by Alexander Bombis.
 *            Email: email@alexander-bombis.de
 */
namespace Partarum\HTTP\Request\Material {

    class Extension {

        public $headers;

        public string $realURL;

        public $cacheTime;

        public function __construct(string $realUrl, string $ext, $cacheTime = null){

            $this->realURL = $realUrl;

            // print_r("function constructor: realURL: " . $this->realURL);

            $this->cacheTime = $cacheTime;

            $this->headers = Headers::setHeader($ext);

            if($ext !== '') {

                $this->{$ext}();
            }
        }

        public static function action(string $realUrl, string $ext, $cacheTime = null): void {

            (new Extension($realUrl, $ext, $cacheTime));
        }

        protected function js(): void {

            $this->headers->setCacheControl("public", $this->cacheTime["js"] ?? null);

            $this->headers->setContentDisposition($this->realURL);

            // print_r("function js: realURL: " . $this->realURL);

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

        protected function txt(){

        }

        protected function php(): void {

            require_once($this->realURL);
        }

        protected function zip(): void {

            //echo $this->realURL;

            readfile($this->realURL);
        }

        protected function jpg(){

            $this->headers->setContentDisposition($this->realURL);

            readfile($this->realURL);
        }

    }
}