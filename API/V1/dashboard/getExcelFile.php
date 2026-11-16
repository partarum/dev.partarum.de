<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

use Partarum\Partarum;
use Partarum\PartarumPHP\Helper\Date\DateFormatter;
use Partarum\PartarumPHP\Helper\Spreadsheet\Excel;
use Partarum\PartarumPHP\HTTP\API;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

if (!class_exists(Partarum::class)) {
    require_once dirname(__DIR__, 3) . "/Partarum/Partarum.php";
}

$invalidPostValues = ["", null];

$backend = new Partarum("getExcelFile");

$api = new API();

$postKeyCheckALL = $api->fromPOST("all");
$requestType = $api->fromPOST("type");

$surveyContentColumn = "survey_excel_character_content";

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

    $resultDate = [];

    foreach ($postKeys as $key => $value) {
        $resultDate[$key] = $db->get("survey_dashboard", "survey_start", ["topic" => $key]);
    }

    $spreadsheet = new Spreadsheet();

    if (!defined("START_CELL_COLUMN")) {
        define("START_CELL_COLUMN", "A");
    }

    if (!defined("START_CELL_ROW")) {
        define("START_CELL_ROW", "1");
    }

    $resultDB = [];

    foreach ($postKeys as $key => $value) {

        if (!in_array($postKeyCheckALL, $invalidPostValues, true)) {

            $dbAllResult = $db->select("survey", $surveyContentColumn, [
                "topic" => $key
            ]);

            if (is_array($dbAllResult) && count($dbAllResult) > 0) {
                $resultDB[$key] = $dbAllResult;
            }

        } elseif (!in_array($value, $invalidPostValues, true)) {

            $dbTopicResult = $db->select("survey", $surveyContentColumn, [
                "topic" => $key
            ]);

            if (is_array($dbTopicResult) && count($dbTopicResult) > 0) {
                $resultDB[$key] = $dbTopicResult;
            }
        }
    }

    $sheetCounter = 0;

    foreach ($resultDB as $key => $value) {

        $rawDate = is_string($resultDate[$key] ?? null) && $resultDate[$key] !== ""
            ? $resultDate[$key]
            : (string)DateFormatter::format_DE(time(), null, "yyyy-MM-dd");

        $formattedDate = (string)DateFormatter::format_DE($rawDate, "yyyy-MM-dd", "MMMM yyyy");
        $testDateArray = explode(" ", $formattedDate);

        $testMonth = $testDateArray[0] ?? "";
        $testYear = $testDateArray[1] ?? "";

        $sheet = new Worksheet($spreadsheet, $key);
        $spreadsheet->addSheet($sheet, $sheetCounter);

        $targetSheet = $spreadsheet->getSheetByName($key);
        if ($targetSheet !== null) {
            $sheetIndex = $spreadsheet->getIndex($targetSheet);
            $spreadsheet->setActiveSheetIndex($sheetIndex);
        }

        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue("A1", $testMonth);
        $activeWorksheet->setCellValue("B1", $testYear);

        $surveyResultCounter = 2;

        foreach ($value as $surveyResult) {
            if (!is_string($surveyResult) || $surveyResult === "") {
                continue;
            }

            $surveyResultArray = json_decode($surveyResult, true);

            if (!is_array($surveyResultArray) || count($surveyResultArray) === 0) {
                continue;
            }

            $lastKey = array_pop($surveyResultArray);
            $newSurveyResultArray = [$lastKey, ...$surveyResultArray];

            $columnLetter = Excel::getCharacter($newSurveyResultArray);

            foreach ($columnLetter as $columnKey => $columnValue) {

                $cellValue = is_scalar($columnValue) || $columnValue === null
                    ? (string)$columnValue
                    : (json_encode($columnValue) ?: "");

                $activeWorksheet->setCellValue($columnKey . $surveyResultCounter, $cellValue);

                if (strlen($cellValue) < 10) {
                    $cell = $sheet->getCell($columnKey . $surveyResultCounter);
                    $style = $cell->getStyle();
                    $alignment = $style->getAlignment();
                    $alignment->setHorizontal("center");
                }
            }

            $surveyResultCounter++;
        }

        $sheetCounter++;
    }

    if ($requestType === "test") {

        $api->createJSONResponse([
            "status" => "ok",
            "topics" => array_keys($resultDB)
        ])->run();

    } else {

        $res = $api->createResponse();

        $res->setHeader("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        $res->setHeader('Content-Disposition: attachment;filename="Erhebung.xlsx"');
        $res->setHeader("Cache-Control: max-age=0");

        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save("php://output");
    }

} else {

    $api->createJSONResponse(["status" => "Your beer is stale - go home!"])->run(403);
}