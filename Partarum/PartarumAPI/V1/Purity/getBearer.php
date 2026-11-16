<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

use Partarum\Partarum;
use Partarum\PartarumPHP\HTTP\API;
use Partarum\PartarumPHP\Security\Token\JWT;

if (!class_exists(Partarum::class)) {
    require_once dirname(__DIR__, 3) . "/Partarum.php";
}

$backend = new Partarum("getBearer");

$api = new API();

$beerPost = $api->fromPOST("beer");
$tokenArray = ($beerPost !== null && $beerPost !== "") ? [$beerPost] : [];

$expiresRaw = $api->fromPOST("expires");
$expires = is_scalar($expiresRaw) && is_numeric((string)$expiresRaw) ? (int)$expiresRaw : 0;

$result = [];
$secretKey = (string)($_ENV["JWT_SECRET_KEY"] ?? "");

if (count($tokenArray) !== 0 && $secretKey !== "") {

    foreach ($tokenArray as $value) {
        if (!is_string($value) && !is_int($value)) {
            continue;
        }

        $jwt = new JWT();
        $jwt->setSecretKey($secretKey);
        $jwt->setPayload([(string)$value, time()]);

        $token = $jwt->getToken();
        $result[(string)$value] = $token;

        $backend->setToCache("Bearer:" . $token, time(), $expires);
    }
}

$api->createJSONResponse(["token" => $result, "post" => $tokenArray, "expires" => $expires])->run();