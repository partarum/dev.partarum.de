<?php
error_reporting(-1);
ini_set("display_errors", "1");

session_start();

use Partarum\Partarum;
use Partarum\PartarumPHP\HTTP\API;
use Partarum\PartarumPHP\Security\Token\JWT;
use Partarum\PartarumPHP\System\Autoloader;

if(!class_exists(Partarum::class)){
    require_once $_SERVER["DOCUMENT_ROOT"] . "/Partarum/Partarum.php";
}

$backend = new Partarum("getBearer");

$backend->addAutoloader( ["Partarum\\PartarumIntern" => $_SERVER["DOCUMENT_ROOT"] . "/Partarum/PartarumIntern"], Autoloader::FOLDER);

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