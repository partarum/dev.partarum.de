<?php
declare(strict_types=1);

use Partarum\Partarum;
use Partarum\PartarumPHP\Helper\Date\DateCalculator;
use Partarum\PartarumPHP\Helper\Date\DateFormatter;
use Partarum\PartarumPHP\HTTP\API;

if (!class_exists(Partarum::class)) {
    require_once dirname(__DIR__, 3) . "/Partarum/Partarum.php";
}

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

$invalidPostValues = ["", null];

$bearerToken = $api->fromBearerToken();

if ($bearerToken !== null && $backend->isIntoCache("Bearer:" . $bearerToken)) {

    $db = $backend->createDBConnection();

    $surveysArray = [];

    foreach ($postKeys as $key => $value) {

        if (!in_array($postKeyCheckALL, $invalidPostValues, true) && is_string($postKeyCheckALL)) {
            $surveysArray[$key] = $postKeyCheckALL;
        } else {
            $surveysArray[$key] = (!in_array($value, $invalidPostValues, true) && is_string($value)) ? $value : null;
        }
    }

    $updateArrayResult = [];
    $diffArray = [];

    foreach ($surveysArray as $key => $value) {

        $now = ($value !== null) ? (string)DateFormatter::format_DE(time(), null, "yyyy-MM-dd") : null;

        $diffInDays = ($value !== null && $now !== null)
            ? (int)DateCalculator::parse($now)->diffInDays($value, false)
            : null;

        $actData = $db->get("survey_dashboard", ["survey_start", "survey_has_been_set", "survey_max_diff"], ["topic" => $key]);
        $actDataArray = is_array($actData) ? $actData : [];

        $actStartSet = $actDataArray["survey_has_been_set"] ?? $now;
        $maxDiff = is_numeric($actDataArray["survey_max_diff"] ?? null) ? (int)$actDataArray["survey_max_diff"] : 0;

        $status = (
            $value !== null
            && $diffInDays !== null
            && $diffInDays > -$maxDiff
            && $diffInDays <= 0
        );

        $stopDateObj = ($value !== null) ? DateCalculator::createFromFormat("Y-m-d", $value) : false;
        $stop = ($stopDateObj instanceof DateCalculator || $stopDateObj instanceof \Carbon\Carbon)
            ? $stopDateObj->copy()->addDays($maxDiff)->format("Y-m-d")
            : null;

        $dbUpdate = [
            "survey_start" => $value,
            "survey_stop" => $stop,
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

    $api->createJSONResponse([
        "started" => $updateArrayResult,
        "status" => $statusArrayResult,
        "diff" => $diffArray
    ])->run();

} else {

    $api->createJSONResponse(["status" => "Your beer is stale", "beer" => $bearerToken])->run(403);
}