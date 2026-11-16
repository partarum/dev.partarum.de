<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\HTTP {

    use JsonException;
    use Partarum\PartarumPHP\HTTP\HTTPHeaderObject;
    use RuntimeException;

    class Data {

        public const REQUEST = 2;

        public const RESPONSE = 4;

        public static mixed $header = null;

        public static function fromGET(?string $needle = null): null | string | array {

            if (count($_GET) > 0) {

                if (isset($needle)) {

                    return $_GET[$needle] ?? null;
                }

                return $_GET;
            }

            return null;
        }

        /**
         * @param string|null $needle
         * @return string|array|null
         */
        public static function fromPOST(?string $needle = null): null | string | array {

            $post = (count($_POST) > 0) ? $_POST : null;

            try {

                $postData = file_get_contents("php://input");

                if (is_string($postData) && trim($postData) !== "") {
                    try {

                        $decoded = json_decode($postData, true, 512, JSON_THROW_ON_ERROR);

                        if (is_array($decoded)) {
                            $post = ($post !== null) ? array_merge($post, $decoded) : $decoded;
                        } elseif ($needle === null && is_string($decoded)) {
                            $post = $decoded;
                        }

                    } catch (JsonException $e) {
                        error_log("[Partarum][Data] JSON decode error in fromPOST: " . $e->getMessage());
                    }
                }
            } catch (RuntimeException $e) {
                error_log("[Partarum][Data] Failed reading php://input: " . $e->getMessage());
            }

            if (isset($post)) {

                if (isset($needle)) {

                    return is_array($post) ? ($post[$needle] ?? null) : null;
                }

                return $post;
            }

            return null;
        }

        public static function fromHeader(?string $needle = null, ?int $flag = null): mixed {

            $headerObject = new HTTPHeaderObject();

            if (isset($flag)) {

                switch ($flag) {

                    case Data::REQUEST:

                        $headerObject->request = self::readRequestHeaders();

                        return (isset($needle)) ? ($headerObject->request[$needle] ?? null) : $headerObject;

                    case Data::RESPONSE:

                        $headerObject->response = self::readResponseHeaders();

                        return (isset($needle)) ? ($headerObject->response[$needle] ?? null) : $headerObject;
                }
            }

            $headerObject->request = self::readRequestHeaders();
            $headerObject->response = self::readResponseHeaders();

            return (isset($needle)) ? (($headerObject->response[$needle] ?? $headerObject->request[$needle]) ?? null) : $headerObject;
        }

        private static function readRequestHeaders(): array {

            if (function_exists("apache_request_headers")) {
                $headers = apache_request_headers();
                if (is_array($headers)) {
                    return $headers;
                }
            }

            $headers = [];
            foreach ($_SERVER as $key => $value) {
                if (str_starts_with((string)$key, "HTTP_")) {
                    $headerName = str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", substr((string)$key, 5)))));
                    $headers[$headerName] = $value;
                } elseif (in_array($key, ["CONTENT_TYPE", "CONTENT_LENGTH", "CONTENT_MD5"], true)) {
                    $headerName = str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", (string)$key))));
                    $headers[$headerName] = $value;
                }
            }

            return $headers;
        }

        private static function readResponseHeaders(): array {

            if (function_exists("apache_response_headers")) {
                $headers = apache_response_headers();
                if (is_array($headers)) {
                    return $headers;
                }
            }

            $headers = [];
            foreach (headers_list() as $rawHeader) {
                $parts = explode(":", $rawHeader, 2);
                if (count($parts) === 2) {
                    $headers[trim($parts[0])] = trim($parts[1]);
                }
            }

            return $headers;
        }
    }
}