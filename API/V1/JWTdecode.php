<?php
declare(strict_types=1);

use Partarum\Partarum;
use Partarum\PartarumPHP\HTTP\API;
use Partarum\PartarumPHP\Security\Token\JWT;

if (!class_exists(Partarum::class)) {
    require_once dirname(__DIR__, 2) . "/Partarum/Partarum.php";
}

$backend = new Partarum();

$api = new API();

$jwt = new JWT();

$post = $api->fromPOST("jwt");
$header = $api->fromHeader("Partarum-Token", API::REQUEST);
$secretKey = is_string($header) && $header !== ""
    ? $header
    : (string)($_ENV["JWT_SECRET_KEY"] ?? "pups");

$token = null;
if (is_string($post) && $post !== "") {
    $token = $post;
} elseif (is_array($post) && isset($post["jwt"]) && is_string($post["jwt"])) {
    $token = $post["jwt"];
} elseif (is_object($post) && isset($post->jwt) && is_string($post->jwt)) {
    $token = $post->jwt;
}

if ($token !== null && $token !== "") {
    $hash = $jwt->checkToken($token, $secretKey, true);

    if ($hash !== false) {
        $api->createJSONResponse($hash)->run();
    } else {
        $api->createJSONResponse("invalid token")->run(403);
    }
} else {
    $api->createJSONResponse("wrong parameter")->run(400);
}