<?php
declare(strict_types=1);

use Partarum\Partarum;
use Partarum\PartarumPHP\HTTP\API;

if (!class_exists(Partarum::class)) {
    require_once dirname(__DIR__, 3) . "/Partarum/Partarum.php";
}

$api = new API();

$dec = $api->fromGET("dec");
$decValue = is_scalar($dec) && is_numeric((string)$dec) ? (int)$dec : 0;

$api->createJSONResponse(decbin($decValue))->run();