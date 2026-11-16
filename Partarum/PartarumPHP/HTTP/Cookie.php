<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\HTTP {

    use Partarum\PartarumPHP\Service\typeArray;

    class Cookie {

        private string $name = "";

        public string $value = "";

        public int | bool $lifetime = 0;

        public string $path = "/";

        public string $domain = "";

        /*
         *  FALSE || TRUE
         *
         *  standard => FALSE
         */
        public bool $secure = false;

        /*
         *  FALSE || TRUE
         *
         *  standard => FALSE
         */
        public bool $httponly = false;

        /*
         *  none || strict || lax
         *
         *  standard => lax
         */
        public string $samesite = self::LAX;

        public const NONE = "none";

        public const STRICT = "strict";

        public const LAX = "lax";

        public const PATH_TO_PARTARUM = "../../../../secura/Crustulum/";

        public const PATH_TO_PATARUM = self::PATH_TO_PARTARUM;

        public array $cookies = [];

        public array $error = [];

        /*
         *  kann ein gültiger Cookie sein || gültige Cookies
         *  kann ein neuer Cookie sein  || mehrere neue Cookies
         */
        public function __construct(string | array | bool $name = false, string | bool $value = false, mixed ...$attr) {

            if ($name !== false) {

                if (is_array($name)) {

                    /*
                     *  id's    (strlen(id) === 5) string
                     *   ||
                     *  attr    (count(attr) >= 2) assoziativ || numeric
                     */
                    if (typeArray::is_Assoc($name)) {

                        $this->setProps($name);

                    } else {

                        $isID = ($name !== []) && typeArray::every(
                            $name,
                            static function ($item): bool {
                                return is_string($item) && (strlen($item) === 5);
                            }
                        );

                        if ($isID === true) {

                            $storageDir = $this->getStorageDir();

                            foreach ($name as $cookieId) {

                                $jsonFile = $storageDir . $cookieId . ".json";
                                $cookieFile = $storageDir . $cookieId . ".cookie";
                                $targetFile = is_file($jsonFile) ? $jsonFile : (is_file($cookieFile) ? $cookieFile : null);

                                if ($targetFile !== null) {
                                    $getJSONCookie = file_get_contents($targetFile);

                                    if ($getJSONCookie !== false) {
                                        $cookieData = json_decode($getJSONCookie, true);

                                        if (is_array($cookieData)) {
                                            $this->cookies[$cookieId] = new CookieObject($cookieData);
                                        }
                                    }
                                }
                            }

                        } else {

                            $this->setProps($name);
                        }
                    }
                } elseif (is_string($name)) {

                    /*
                     *  name || id
                     */
                    if ($value === false) {

                        $storageDir = $this->getStorageDir();
                        $jsonFile = $storageDir . $name . ".json";
                        $cookieFile = $storageDir . $name . ".cookie";
                        $targetFile = is_file($jsonFile) ? $jsonFile : (is_file($cookieFile) ? $cookieFile : null);

                        if ($targetFile !== null && ($getToken = file_get_contents($targetFile)) !== false) {

                            $cookie = json_decode($getToken, true);

                            if (is_array($cookie)) {
                                $this->setProps($cookie);
                            }

                        } else {

                            $this->error["constructor"][] = "token not found | Line 91";
                        }
                    } else {

                        $this->setProps([
                            $name,
                            (string)$value,
                            ...$attr
                        ]);
                    }

                } else {

                    $this->error["constructor"][] = " type of name is not correct : " . gettype($name);
                }
            }
        }

        private function getStorageDir(): string {

            $configured = $_ENV["PARTARUM_COOKIE_PATH"] ?? null;

            if (is_string($configured) && $configured !== "") {
                return rtrim($configured, "/\\") . DIRECTORY_SEPARATOR;
            }

            return self::PATH_TO_PARTARUM;
        }

        private function setProps(array $attr): void {

            if (array_key_exists("name", $attr) || array_key_exists(0, $attr)) {
                $this->name = (string)($attr["name"] ?? $attr[0] ?? "");
            }

            if (array_key_exists("value", $attr) || array_key_exists(1, $attr)) {
                $this->value = (string)($attr["value"] ?? $attr[1] ?? "");
            }

            if (array_key_exists("lifetime", $attr) || array_key_exists("expires", $attr) || array_key_exists(2, $attr)) {
                $rawLifetime = $attr["lifetime"] ?? $attr["expires"] ?? $attr[2] ?? 0;
                $this->lifetime = is_bool($rawLifetime) ? (int)$rawLifetime : (int)$rawLifetime;
            }

            if (array_key_exists("path", $attr) || array_key_exists(3, $attr)) {
                $this->path = (string)($attr["path"] ?? $attr[3] ?? "/");
            }

            if (array_key_exists("domain", $attr) || array_key_exists(4, $attr)) {
                $this->domain = (string)($attr["domain"] ?? $attr[4] ?? "");
            }

            if (array_key_exists("secure", $attr) || array_key_exists(5, $attr)) {
                $this->secure = (bool)($attr["secure"] ?? $attr[5] ?? false);
            }

            if (array_key_exists("httponly", $attr) || array_key_exists(6, $attr)) {
                $this->httponly = (bool)($attr["httponly"] ?? $attr[6] ?? false);
            }

            if (array_key_exists("samesite", $attr) || array_key_exists(7, $attr)) {
                $this->samesite = (string)($attr["samesite"] ?? $attr[7] ?? self::LAX);
            }
        }

        public function setName(string $name): void {

            $this->name = $name;
        }

        public function getName(): string {

            return $this->name;
        }

        public function send(): string {

            return $this->create();
        }

        public function safe(): string {

            return $this->create();
        }

        public function create(): string {

            $expires = is_bool($this->lifetime) ? (int)$this->lifetime : $this->lifetime;

            $optionArray = [
                "expires" => $expires,
                "path" => $this->path,
                "domain" => $this->domain,
                "secure" => $this->secure,
                "httponly" => $this->httponly,
                "samesite" => $this->samesite
            ];

            if ($this->name !== "" && !headers_sent()) {
                setcookie(
                    $this->name,
                    $this->value,
                    $optionArray
                );
            }

            $cookieArray = [
                "name" => $this->name,
                "value" => $this->value
            ] + $optionArray;

            $cookieJSON = (string)json_encode($cookieArray);

            $id = substr(bin2hex(random_bytes(3)), 0, 5);

            $storageDir = $this->getStorageDir();

            if (is_dir($storageDir) && is_writable($storageDir)) {
                file_put_contents($storageDir . $id . ".cookie", $cookieJSON);
            }

            return $id;
        }

        public function kill(): bool {

            return self::destroy($this->name);
        }

        /*
         *  name, value || alle attribute hintereinander || array mit den Attributen || id || id's
         */
        public static function destroy(string | array $name = "", string $value = "", mixed ...$attr): bool {

            $cookieName = is_array($name) ? (string)($name["name"] ?? $name[0] ?? "") : $name;

            if ($cookieName !== "" && isset($_COOKIE[$cookieName])) {

                if (!headers_sent()) {
                    setcookie($cookieName, "", 1);
                }

                unset($_COOKIE[$cookieName]);

                return true;
            }

            return false;
        }
    }
}

