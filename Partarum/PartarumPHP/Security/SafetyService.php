<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Security {


    use Generator;
    use JsonException;
    use Partarum\PartarumPHP\HTTP\APIRequest;
    use Partarum\PartarumPHP\HTTP\Header\Entity\Allow;
    use Partarum\PartarumPHP\HTTP\Request\REST;
    use Partarum\PartarumPHP\Security\Token\JWT;

    class SafetyService {

        public static array $stringReplace = ['\r', '\n'];
        public static ?string $safetyString = null;
        public static mixed $result = null;

        public function __construct(){

            //new SafetyFirst();
        }

        public static function safetyRequestComeIn(): array {

            return [
                "bad" => [
                    "visited" => false
                ],
                "good" => [
                    "visited" => true,
                    "cookies" => session_id(),
                    "visitingDate" => "",
                    "anonym" => false
                ]
            ];
        }

        public static function isEmail(string | array $item): Generator {

            $item = (is_array($item)) ? $item : [$item];

            foreach($item as $value){

                $result = filter_var($value, FILTER_VALIDATE_EMAIL);

                yield $result;
            }
        }

        public static function hash512(string | array $item): Generator {

            $pureItem = (is_array($item)) ? $item : [$item];

            foreach($pureItem as $value){

                $result = hash("sha512", (string)$value);

                yield $result;
            }
        }

        public static function isEqualValue(mixed $firstItem, mixed $secondItem): Generator {

            $pureFirstItem = (is_array($firstItem)) ? $firstItem : [$firstItem];
            $pureSecondItem = (is_array($secondItem)) ? $secondItem : [$secondItem];

            foreach($pureFirstItem as $key => $value){

                if(!array_key_exists($key, $pureSecondItem)){
                    yield false;
                    continue;
                }

                $secondValue = $pureSecondItem[$key];

                if(is_string($value) && is_string($secondValue)){
                    yield hash_equals($value, $secondValue) ? $value : false;
                } elseif($value === $secondValue){
                    yield $value;
                } else {
                    yield false;
                }
            }
        }

        public static function safetyUser(array $item): array {

            return array_map(static function ($x) {
                return is_string($x) ? trim($x) : $x;
            }, $item);
        }

        public static function makeSafetyString(string $testZero): string {

            $checkedString = str_replace(self::$stringReplace, "FALSE", $testZero);

            self::$safetyString = $checkedString;

            return $checkedString;
        }

        public static function base64UrlEncode(array $array): Generator {

            foreach ($array as $item) {

                yield str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($item));
            }
        }

        public static function createJWT(array $payload, string $key): string {

            $jwt = new JWT();

            $jwt->setSecretKey($key);

            $jwt->setPayload($payload);

            return $jwt->getToken();
        }

        public static function checkJWT(?string $token, string $key): bool {

            $jwt = new JWT();

            return $jwt->checkToken($token, $key);
        }

        public static function encodeJWT(array $payload, string $key): string {

            return self::createJWT($payload, $key);
        }

        public static function decodeJWT(?string $token, string $key): bool {

            return self::checkJWT($token, $key);
        }

        public static function createUserToken(string $project, ?string $apiUrl = null): mixed {

            if(!isset(LicentiamObject::PROJECT[$project])) {
                return null;
            }

            $request = new APIRequest();

            $request->setURL($apiUrl ?? $_ENV["PARTARUM_AUTH_API_URL"] ?? "https://partarum.de/API/V1/Gratissimum");

            $request->setMethod(Allow::POST);

            $request->contentType = REST::JSON;

            $request->content = [
                "customToken" => LicentiamObject::LOREM,
                "projectToken" => LicentiamObject::PROJECT[$project]
            ];

            try {

                return $request->run();

            } catch (JsonException $e) {

                error_log("SafetyService::createUserToken JsonException: " . $e->getMessage());
            }

            return null;
        }
    }
}
