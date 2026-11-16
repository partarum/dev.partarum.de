<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

use Partarum\Partarum;
use Partarum\PartarumPHP\Helper\Date\DateCalculator;
use Partarum\PartarumPHP\Helper\Date\DateFormatter;
use Partarum\PartarumPHP\HTTP\API;

if (!class_exists(Partarum::class)) {
    require_once dirname(__DIR__, 3) . "/Partarum/Partarum.php";
}

$backend = new Partarum("checkSurvey");

$api = new API();

$postKeys = [
    "KVH" => $api->fromPOST("kvh"),
    "LSH" => $api->fromPOST("lsh"),
    "NSH" => $api->fromPOST("nsh"),
    "RHB" => $api->fromPOST("rhb"),
    "RHD" => $api->fromPOST("rhd")
];

$bearerToken = $api->fromBearerToken();

function validationDate(string $postDate): array {

    $formatted = DateFormatter::format_DE($postDate, "yyyy-MM-dd", [
        "date" => "yyyy-MM-dd",
        "datetime" => "yyyy-MM",
        "displayDate" => "MMMM yyyy"
    ]);

    return is_array($formatted) ? $formatted : [];
}

if ($bearerToken !== null && $backend->isIntoCache("Bearer:" . $bearerToken)) {

    $db = $backend->createDBConnection();

    $resultStart = [];
    $resultDisplayDate = [];
    $resultALL = [];
    $statusArray = [];
    $countArrayResult = [];

    $now = (string)DateFormatter::format_DE(time(), null, "yyyy-MM-dd");

    foreach ($postKeys as $key => $value) {

        $rows = $db->select("survey_dashboard", [
            "survey_start",
            "survey_stop",
            "survey_has_been_set",
            "survey_has_been_stopped",
            "survey_status",
            "survey_diff",
            "survey_max_diff"
        ], [
            "topic" => $key
        ]);

        $resultALL[$key] = is_array($rows) ? $rows : [];
        $surveyStart = $resultALL[$key][0]["survey_start"] ?? null;
        $resultStart[$key] = is_string($surveyStart) && $surveyStart !== "" ? $surveyStart : null;

        $diffInDays = ($resultStart[$key] !== null)
            ? (int)DateCalculator::parse($now)->diffInDays($resultStart[$key], false)
            : null;

        $maxDiffRaw = $resultALL[$key][0]["survey_max_diff"]
            ?? $db->get("survey_dashboard", "survey_max_diff", ["topic" => $key]);
        $maxDiff = is_numeric($maxDiffRaw) ? (int)$maxDiffRaw : 0;

        $status = (
            $resultStart[$key] !== null
            && $diffInDays !== null
            && $diffInDays >= -$maxDiff
            && $diffInDays <= $maxDiff
        );

        $statusArray[$key] = $status;

        $resultDisplayDate[$key] = ($resultStart[$key] !== null)
            ? validationDate($resultStart[$key])
            : "0000-00-00";

        $db->update("survey_dashboard", [
            "survey_status" => $status,
            "survey_diff" => $diffInDays
        ], [
            "topic" => $key
        ]);

        if (!isset($resultALL[$key][0]) || !is_array($resultALL[$key][0])) {
            $resultALL[$key][0] = [];
        }

        $resultALL[$key][0]["survey_status"] = $status;
        $resultALL[$key][0]["survey_diff"] = $diffInDays;

        $countArrayResult[$key] = (int)$db->count("survey", [
            "topic" => $key
        ]);
    }

    $api->createJSONResponse([
        "survey_start" => $resultStart,
        "status" => $statusArray,
        "all" => $resultALL,
        "displayDate" => $resultDisplayDate,
        "surveyCount" => $countArrayResult
    ])->run();

} else {

    $api->createJSONResponse(["status" => "Your beer is stale", "beer" => $bearerToken])->run(403);
}