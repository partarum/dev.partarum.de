<?php
declare(strict_types=1);

use Partarum\Partarum;
use Partarum\PartarumPHP\HTTP\API;

if (!class_exists(Partarum::class)) {
    require_once dirname(__DIR__, 2) . "/Partarum/Partarum.php";
}

$api = new API();

$api->createJSONResponse($api->createUUID())->run();