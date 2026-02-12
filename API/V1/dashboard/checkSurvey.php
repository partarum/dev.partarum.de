<?php
error_reporting(-1);
ini_set("display_errors", "1");

session_start();

if (!class_exists('Partarum')) {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/Partarum/Partarum.php";
}

use Partarum\HTTP\API;
use Partarum\Helper\Date\DateFormatter;
use Partarum\Helper\Date\DateCalculator;

$backend = new Partarum("checkSurvey");

$api = new API();

$postKeys = [
    "KVH" => $api->fromPOST("kvh"),
    "LSH" => $api->fromPOST("lsh"),
    "NSH" => $api->fromPOST("nsh"),
    "RHB" => $api->fromPOST("rhb"),
    "RHD" => $api->fromPOST("rhd")
];

$bearer = explode("Bearer ", $api->fromHeader("Authorization", API::REQUEST));

function validationDate(string $postDate) :  array {

    return DateFormatter::format_DE($postDate, "yyyy-MM-dd", [
        "date" => "yyyy-MM-dd",
        "datetime" => "yyyy-MM",
        "displayDate" => "MMMM yyyy"
    ]);
}

if($backend->isIntoCache("Bearer:" . $bearer[1])){

    $db = $backend->createDBConnection();

    $resultStart = [];

    $resultDisplayDate = [];

    $resultALL = [];

    $statusArray = [];

    $countArrayResult = [];

    foreach($postKeys as $key => $value) {

        $resultALL[$key] = $db->select("survey_dashboard", ["survey_start", "survey_stop", "survey_has_been_set", "survey_has_been_stopped", "survey_status", "survey_diff", "survey_max_diff"], ["topic" => $key]);

        $resultStart[$key] = $resultALL[$key][0]["survey_start"];

        $now = DateFormatter::format_DE(time(), null, "yyyy-MM-dd");

        $diffInDays = DateCalculator::parse($now)->diffInDays($resultStart[$key], false);


        $maxDiff = $db->get("survey_dashboard", "survey_max_diff", ["topic" => $key]);

        $status = (($resultStart[$key] !== null) && (($diffInDays >= -$maxDiff) && ($diffInDays <= $maxDiff)));

        $statusArray[$key] = $status;

        $resultDisplayDate[$key] = ($resultStart[$key] !== null) ? validationDate($resultStart[$key]) : "0000-00-00";


        $db->update("survey_dashboard", [
            "survey_status" => $status,
            "survey_diff" => $diffInDays
        ], [
            "topic" => $key
        ]);

        $resultAll[$key][0]["survey_status"] = $status;

        $resultAll[$key][0]["survey_diff"] = ($resultStart[$key] !== null) ? $diffInDays : null;

        $countArrayResult[$key] = $db->count("survey", [
            "topic" => $key
        ]);

    }


    $api->createJSONResponse(["survey_start" => $resultStart, "status" => $statusArray, "all" => $resultALL, "displayDate" => $resultDisplayDate, "surveyCount" => $countArrayResult])->run();

} else {

    $api->createJSONResponse(["status" => "Your beer is stale", "beer" => $bearer])->run(403);
}