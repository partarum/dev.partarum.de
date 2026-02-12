<?php
//error_reporting(-1);
//ini_set("display_errors", "1");

//session_start();

if (!class_exists('Partarum')) {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/Partarum/Partarum.php";
}

use Carbon\Carbon;
use Partarum\HTTP\API;
use Partarum\Helper\Date\DateFormatter;
use Partarum\Helper\Date\DateCalculator;

$backend = new Partarum("startSurvey");

$api = new API();

$postKeyCheckALL = $api->fromPOST("all");

$postKeys = [
    "KVH" => $api->fromPOST("kvh"),
    "LSH" => $api->fromPOST("lsh"),
    "NSH" => $api->fromPOST("nsh"),
    "RHB" => $api->fromPOST("rhb"),
    "RHD" => $api->fromPOST("rhd")
];

$invalidPostValues =  ["", null];

$bearer = explode("Bearer ", $api->fromHeader("Authorization", API::REQUEST));

if($backend->isIntoCache("Bearer:" . $bearer[1])){

    $db = $backend->createDBConnection();

    $surveysArray = [];

    foreach ($postKeys as $key => $value) {

        if (!in_array($postKeyCheckALL, $invalidPostValues)) {

            $surveysArray[$key] = $postKeyCheckALL;

        } else {

            $surveysArray[$key] = (in_array($value, $invalidPostValues)) ? null : $value;
        }
    }

    $updateArrayResult = [];

    $diffArray = [];

    $formatArray = [];

    foreach ($surveysArray as $key => $value) {

        $now = ($value !== null) ? DateFormatter::format_DE(time(), null, "yyyy-MM-dd") : null;

        $diffInDays = ($value !== null) ? DateCalculator::parse($now)->diffInDays($value, false) : null;

        $actData = $db->get("survey_dashboard", ["survey_start", "survey_has_been_set", "survey_max_diff"], ["topic" => $key]);

        $actStartSet = $actData["survey_has_been_set"] ?? $now;

        $maxDiff = $actData["survey_max_diff"];

        $status = (($value !== null) && (($diffInDays > -$maxDiff) && ($diffInDays <= 0)));

        $stop = ($value !== null) ? DateCalculator::createFromFormat("Y-m-d", $value)->addDays($maxDiff) : null;

        $dbUpdate = [
            "survey_start" => $value,
            "survey_stop" => ($stop !== null) ? $stop->format("Y-m-d") : null,
            "survey_has_been_set" => (($now !== null) && ($actStartSet !== $now)) ? $actStartSet : $now,
            "survey_has_been_stopped" => null,
            "survey_status" => $status,
            "survey_diff" => $diffInDays
        ];

        $diffArray[$key] = $dbUpdate;

        $db->update("survey_dashboard", $dbUpdate, [
            "topic" => $key
        ]);

        $updateArrayResult[] = $key;
    }

    $statusArrayResult = [];

    foreach ($postKeys as $key => $value) {

        $statusArrayResult[$key] = $db->get("survey_dashboard", "survey_start", [
            "topic" => $key
        ]);
    }

    $api->createJSONResponse(["started" => $updateArrayResult, "status" => $statusArrayResult, "diff" => $diffArray])->run();

} else {

    $api->createJSONResponse(["status" => "Your beer is stale", "beer" => $bearer])->run(403);
}