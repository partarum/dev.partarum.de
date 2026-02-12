<?php
error_reporting(-1);
ini_set("display_errors", "1");

session_start();

if (!class_exists('Partarum')) {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/Partarum/Partarum.php";
}

use Partarum\Helper\Date\DateFormatter;
use Partarum\HTTP\API;
use Partarum\Security\Token\JWT;

$backend = new Partarum("getToken");

$api = new API();

$postKeyCheckALL = $api->fromPOST("all");

$postKeys = [
    "KVH" => $api->fromPOST("kvh"),
    "LSH" => $api->fromPOST("lsh"),
    "NSH" => $api->fromPOST("nsh"),
    "RHB" => $api->fromPOST("rhb"),
    "RHD" => $api->fromPOST("rhd")
];

$bearer = explode("Bearer ", $api->fromHeader("Authorization", API::REQUEST));

if($backend->isIntoCache("Bearer:" . $bearer[1])){

    $db = $backend->createDBConnection();

    $resultDB = [];

    foreach ($postKeys as $key => $value) {

        if ($postKeyCheckALL !== NULL) {

            $resultDB[$key] = $db->get("survey_dashboard", "survey_start", [
                "topic" => $key
            ]);

        } else {

            if ($value !== null) {

                $resultDB[$key] = $db->get("survey_dashboard", "survey_start", [
                    "topic" => $key
                ]);
            } else {

                unset($resultDB[$key]);
            }
        }
    }

    $updateArrayResult = [];

    $countArrayResult = [];

    $deleteArrayResult = [];

    foreach ($resultDB as $key => $value) {

        $now = ($value !== null) ? DateFormatter::format_DE(time(), null, "yyyy-MM-dd") : null;

        $countArrayResult[$key] = $db->count("survey", [
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

        $deleteArrayResult[$key] = $deletedResult->rowCount();
    }

    $statusArrayResult = [];

    foreach ($postKeys as $key => $value) {

        $statusArrayResult[$key] = $db->get("survey_dashboard", "survey_start", [
            "topic" => $key
        ]);
    }

    $api->createJSONResponse(["stopped" => $updateArrayResult, "status" => $statusArrayResult, "stoppedCount" => $countArrayResult, "deletedCount" => $deleteArrayResult])->run();

} else {

    $api->createJSONResponse(["status" => "Your beer is stale", "beer" => $bearer])->run(403);
}