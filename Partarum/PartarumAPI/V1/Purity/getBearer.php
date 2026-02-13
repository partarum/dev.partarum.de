<?php
error_reporting(-1);
ini_set("display_errors", "1");

session_start();

if(!class_exists('Partarum')){
    require_once $_SERVER["DOCUMENT_ROOT"] . "/Partarum/Partarum.php";
}

use Partarum\HTTP\API;
use Partarum\Security\Token\JWT;
use Partarum\System\Autoloader;

$backend = new Partarum("getBearer");

$backend->addAutoloader( ["PartarumIntern" => $_SERVER["DOCUMENT_ROOT"] . "/Partarum/PartarumIntern"], Autoloader::FOLDER);

$api = new API();

$tokenArray = [$api->fromPOST("beer")];

$expires = $api->fromPOST("expires") ?? 0;

$result = [];

if(count($tokenArray) !== 0){

    foreach($tokenArray as $key => $value){

        $jwt = new JWT();

        $jwt->setSecretKey($_ENV["JWT_SECRET_KEY"]);

        $jwt->setPayload([$value, time()]);

        $result[$value] = $jwt->getToken();

        $backend->setToCache("Bearer:" . $result[$value], time(), $expires);
    }
}

$api->createJSONResponse(["token" => $result, "post" => $tokenArray, "expires" => $expires])->run();