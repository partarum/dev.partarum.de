<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

use Partarum\Partarum;
use Partarum\PartarumPHP\HTTP\API;
use Partarum\PartarumPHP\Security\Token\JWT;

if (!class_exists(Partarum::class)) {
    require_once dirname(__DIR__, 3) . "/Partarum/Partarum.php";
}

$backend = new Partarum("getToken");

$api = new API();

$postKeyCheckALL = $api->fromPOST("all");

$postKeys = [
    "kvh" => $api->fromPOST("kvh"),
    "lsh" => $api->fromPOST("lsh"),
    "nsh" => $api->fromPOST("nsh"),
    "rhb" => $api->fromPOST("rhb"),
    "rhd" => $api->fromPOST("rhd")
];

$bearerToken = $api->fromBearerToken();

if ($bearerToken !== null && $backend->isIntoCache("Bearer:" . $bearerToken)) {

    $db = $backend->createDBConnection();

    $result = [];
    $secretKey = (string)($_ENV["JWT_SECRET_KEY"] ?? "");

    foreach ($postKeys as $key => $value) {

        if ($postKeyCheckALL !== null || $value !== null) {
            $jwt = new JWT();
            $jwt->setSecretKey($secretKey);
            $jwt->setPayload([$key]);

            $result[$key] = $jwt->getToken();
        }
    }

    $insertArray = [];
    $insertArrayResult = [];
    $updateArrayResult = [];

    foreach ($result as $key => $value) {

        if ($db->has("token", ["name" => $key])) {

            $db->update("token", [
                "value" => $value
            ], [
                "name" => $key
            ]);

            $updateArrayResult[] = $key;

        } else {

            $insertArray[] = [
                "name" => $key,
                "value" => $value
            ];

            $insertArrayResult[] = $key;
        }
    }

    if (count($insertArray) > 0) {
        $db->insert("token", $insertArray);
    }

    $api->createJSONResponse([
        "token" => $result,
        "insert" => $insertArrayResult,
        "update" => $updateArrayResult
    ])->run();

} else {

    $api->createJSONResponse(["status" => "Your beer is stale", "beer" => $bearerToken])->run(403);
}