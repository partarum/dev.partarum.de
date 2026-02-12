<?php
error_reporting(-1);
ini_set("display_errors", "1");

session_start();

if (!class_exists('Partarum')) {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/Partarum/Partarum.php";
}

use Partarum\HTTP\API;
use Partarum\Security\Token\JWT;

$backend = new Partarum("getExcelData");

$api = new API();

$bearer = explode("Bearer ", $api->fromHeader("Authorization", API::REQUEST));

if($backend->isIntoCache("Bearer:" . $bearer[1])){

    $db = $backend->createDBConnection();

    $surveyKeys = ["KVH", "LSH", "NSH", "RHB", "RHD"];

    $result = [];

    $counter = 0;

    foreach($surveyKeys as $value) {

        $result[$value] = $db->count("survey", ["topic" => $value]);

        $counter = $counter + $result[$value];
    }

    $result["ALL"] = $counter;

    $api->createJSONResponse(["survey_count" => $result])->run();

} else {

    $api->createJSONResponse(["status" => "Your beer is stale", "beer" => $bearer])->run(403);
}