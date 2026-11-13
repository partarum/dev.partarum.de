<?php
error_reporting(-1);
ini_set("display_errors", "1");

if(!class_exists(\Partarum\Partarum::class)){
    require_once $_SERVER["DOCUMENT_ROOT"] . "/Partarum/Partarum.php";
}
use Partarum\Partarum;

use Partarum\PartarumPHP\HTTP\API;
use Partarum\PartarumPHP\Security\Token\JWT;
use Partarum\PartarumPHP\System\Autoloader;

$backend = new Partarum();

$backend->addAutoloader( ["Partarum\\PartarumIntern" => $_SERVER["DOCUMENT_ROOT"] . "/Partarum/PartarumIntern"], Autoloader::FOLDER);

$api = new API();

$jwt = new JWT();

$post = $api->fromPOST("jwt");

if($token = $post->jwt ?? NULL) {

    $check = ($hash = $jwt->checkToken($token, "pups", TRUE)) ? 1 : 0;

    ($check === 1) && $api->createJSONResponse($hash)->run();
} else {

    $api->createJSONResponse("wrong parameter")->run();
}