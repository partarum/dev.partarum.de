<?php

if(!class_exists(\Partarum\Partarum::class)){
    require_once $_SERVER["DOCUMENT_ROOT"] . "src/Partarum.php";
}

use Partarum\PartarumPHP\HTTP\API;

$api = new API();

$api->createJSONResponse($api->createUUID())->run();