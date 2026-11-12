<?php
/*
 *   Copyright 2018- 2021 © Alexander Bombis. All rights reserved.
 *            Developed by Alexander Bombis.
 *            Email: email@alexander-bombis.de
 */
namespace Partarum\PartarumPHP\HTTP\Request\Material {

    use Partarum\HTTP\Request\Material\Headers;

    class Extension {

        public $headers;

        public string $realURL;

        public $cacheTime;

        public function __construct(string $realUrl, string $ext, $cacheTime = null){

            $this->realURL = $realUrl;

            $this->cacheTime = $cacheTime;

            $this->headers = Headers::setHeader($ext);

            if($ext !== '') {

//                print_r($ext);
//                print_r("---------");
//                print_r($realUrl);

                if(method_exists($this, $ext)) {
                    $this->{$ext}();
                } else {
                    http_response_code(404);
                }

            }
        }

        public static function action(string $realUrl, string $ext, $cacheTime = null): void {

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

        protected function txt(){

        }

        protected function php(): void {


            if (file_exists($this->realURL)) {
                require_once($this->realURL);

            } else {
                http_response_code(404);
            }
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