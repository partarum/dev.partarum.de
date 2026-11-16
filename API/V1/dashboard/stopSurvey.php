<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

use Partarum\Partarum;
use Partarum\PartarumPHP\Helper\Date\DateFormatter;
use Partarum\PartarumPHP\HTTP\API;

if (!class_exists(Partarum::class)) {
    require_once dirname(__DIR__, 3) . "/Partarum/Partarum.php";
}

$backend = new Partarum("stopSurvey");

$api = new API();

$postKeyCheckALL = $api->fromPOST("all");

$postKeys = [
    "KVH" => $api->fromPOST("kvh"),
    "LSH" => $api->fromPOST("lsh"),
    "NSH" => $api->fromPOST("nsh"),
    "RHB" => $api->fromPOST("rhb"),
    "RHD" => $api->fromPOST("rhd")
];

$bearerToken = $api->fromBearerToken();

if ($bearerToken !== null && $backend->isIntoCache("Bearer:" . $bearerToken)) {

    $db = $backend->createDBConnection();

    $resultDB = [];

    foreach ($postKeys as $key => $value) {

        if ($postKeyCheckALL !== null || $value !== null) {
            $resultDB[$key] = $db->get("survey_dashboard", "survey_start", [
                "topic" => $key
            ]);
        }
    }

    $updateArrayResult = [];
    $countArrayResult = [];
    $deleteArrayResult = [];

    foreach ($resultDB as $key => $value) {

        $now = ($value !== null) ? (string)DateFormatter::format_DE(time(), null, "yyyy-MM-dd") : null;

        $countArrayResult[$key] = (int)$db->count("survey", [
            "topic" => $key
        ]);

        $db->update("survey_dashboard", [
            "survey_start" => null,
            "survey_stop" => null,
            "survey_has_been_set" => null,
            "survey_has_been_stopped" => $now,
            "survey_status" => 0,
            "survey_diff" => null
        ], [
            "topic" => $key
        ]);

        $updateArrayResult[] = $key;

        $deletedResult = $db->delete("survey", [
            "topic" => $key
        ]);

        $deleteArrayResult[$key] = ($deletedResult instanceof \PDOStatement) ? $deletedResult->rowCount() : 0;
    }

    $statusArrayResult = [];

    foreach ($postKeys as $key => $value) {

        $statusArrayResult[$key] = $db->get("survey_dashboard", "survey_start", [
            "topic" => $key
        ]);
    }

    $api->createJSONResponse([
        "stopped" => $updateArrayResult,
        "status" => $statusArrayResult,
        "stoppedCount" => $countArrayResult,
        "deletedCount" => $deleteArrayResult
    ])->run();

} else {

    $api->createJSONResponse(["status" => "Your beer is stale", "beer" => $bearerToken])->run(403);
}