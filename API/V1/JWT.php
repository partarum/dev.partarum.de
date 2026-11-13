<?php

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

$post = $api->fromPOST("hallo");

$header = $api->fromHeader("Partarum-Token", API::REQUEST);


$jwt = new JWT();

$jwt->setSecretKey("pups"); // $header - der Partarum - Token welcher vergeben wird !!!!

$jwt->setPayload($post);

echo json_encode([$jwt->getToken()], JSON_THROW_ON_ERROR);