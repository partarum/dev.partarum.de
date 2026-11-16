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

$post = $api->fromPOST("hallo");
$header = $api->fromHeader("Partarum-Token", API::REQUEST);

$secretKey = is_string($header) && $header !== ""
    ? $header
    : (string)($_ENV["JWT_SECRET_KEY"] ?? "pups");

$jwt = new JWT();
$jwt->setSecretKey($secretKey);
$jwt->setPayload(is_array($post) ? $post : ($post !== null ? [$post] : []));

$api->createJSONResponse([$jwt->getToken()])->run();