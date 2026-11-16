<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

use Partarum\Partarum;
use Partarum\PartarumPHP\HTTP\API;

if (!class_exists(Partarum::class)) {
    require_once dirname(__DIR__, 3) . "/Partarum/Partarum.php";
}

$backend = new Partarum("getExcelData");

$api = new API();

$bearerToken = $api->fromBearerToken();

if ($bearerToken !== null && $backend->isIntoCache("Bearer:" . $bearerToken)) {

    $db = $backend->createDBConnection();

    $surveyKeys = ["KVH", "LSH", "NSH", "RHB", "RHD"];

    $result = [];
    $counter = 0;

    foreach ($surveyKeys as $value) {

        $count = (int)$db->count("survey", ["topic" => $value]);
        $result[$value] = $count;
        $counter += $count;
    }

    $result["ALL"] = $counter;

    $api->createJSONResponse(["survey_count" => $result])->run();

} else {

    $api->createJSONResponse(["status" => "Your beer is stale", "beer" => $bearerToken])->run(403);
}