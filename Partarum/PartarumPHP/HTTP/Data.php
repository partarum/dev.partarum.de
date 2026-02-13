<?php
namespace Partarum\PartarumPHP\HTTP {

    use JsonException;
    use Partarum\HTTP\HTTPHeaderObject;
    use RuntimeException;

    class Data {

        public const REQUEST = 2;

        public const RESPONSE = 4;

        public static $header;
        
        
        public static function __callStatic($name, $arg){
            
            switch($name) {
                    
                case "fromGET":
                    
                    //return self::fromGET($arg);
                    return "static Pups";
                    break;
            }
        }
        
        public function __call($name, $arg){
            
            switch($name) {
                    
                case "fromGET":
                    
                    //return self::fromGET($arg);
                    return "non static Pups";
                    break;
            }
        }

        
        public static function fromGET(?string $needle = NULL) : null | string | array {
            
            if(count($_GET) > 0){

                if(isset($needle)){

                    return $_GET[$needle] ?? NULL;
                } else {

                    return $_GET;
                }
            }

            return NULL;
        }


        /**
         * @param string|null $needle
         * @return string|array|null
         */
        public static function fromPOST(?string $needle = NULL) : null | string | array {

            $post = NULL;

            if(count($_POST) > 0){

                $post = $_POST;

            }

            try {

                $postData = file_get_contents("php://input");

                try {

                    $post = json_decode($postData, TRUE, 512, JSON_THROW_ON_ERROR);

                } catch (JsonException $e) {

                }
            } catch(RuntimeException $e){

            }

            if(isset($post)){

                if(isset($needle)){

                    return $post[$needle] ?? NULL;
                } else {

                    return $post;
                }
            }

            return NULL;
        }

        public static function fromHeader(?string $needle = NULL, ?int $flag = NULL) {

            $headerObject = new HTTPHeaderObject();

            if(isset($flag)){

                switch($flag) {

                    case DATA::REQUEST:

                        $headerObject->request = apache_request_headers();

                        return (isset($needle)) ? $headerObject->request[$needle] ?? NULL : $headerObject;

                    case DATA::RESPONSE:

                        $headerObject->response = apache_response_headers();

                        return (isset($needle)) ? $headerObject->response[$needle] ?? NULL : $headerObject;
                }
            }

            $headerObject->request = apache_request_headers();

            if(function_exists("apache_response_headers")) {
                $headerObject->response = apache_response_headers();
            }

            return (isset($needle)) ? ($headerObject->response[$needle] ?? $headerObject->request[$needle]) ?? NULL : $headerObject;

        }
    }
}