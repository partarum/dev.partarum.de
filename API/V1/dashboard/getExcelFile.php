<?php
error_reporting(-1);
ini_set("display_errors", "1");

session_start();

if (!class_exists('Partarum')) {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/Partarum/Partarum.php";
}

use Partarum\Helper\Date\DateFormatter;
use Partarum\Helper\Spreadsheet\Excel;
use Partarum\HTTP\API;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


$invalidPostValues =  ["", null];

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


$bearer = explode("Bearer ", $api->fromHeader("Authorization", API::REQUEST));

if ($backend->isIntoCache("Bearer:" . $bearer[1])) {

    $db = $backend->createDBConnection();

    $resultDate = [];

    foreach ($postKeys as $key => $value) {

        $resultDate[$key] = $db->get("survey_dashboard", "survey_start", ["topic" => $key]);
    }

    $spreadsheet = new Spreadsheet();

    define("START_CELL_COLUMN", "A");

    define("START_CELL_ROW", "1");

    $resultDB = [
        "KVH" => "",
        "LSH" => "",
        "NSH" => "",
        "RHB" => "",
        "RHD" => ""
        ];

    foreach ($postKeys as $key => $value) {

        if (!in_array($postKeyCheckALL, $invalidPostValues)) {

            $dbAllResult = $db->select("survey", $surveyContentColumn, [
                "topic" => $key
            ]);

            if(count($dbAllResult) > 0){

                $resultDB[$key] = $dbAllResult;

            } else {

                unset($resultDB[$key]);
            }


        } else {

            if ($value !== null) {

                $resultDB[$key] = $db->select("survey", $surveyContentColumn, [
                    "topic" => $key
                ]);
            } else {

                unset($resultDB[$key]);
            }
        }
    }

    $sheetArray = [];

    $sheetCounter = 0;

    foreach($resultDB as $key => $value) {

        $testDateArray = explode(" ", DateFormatter::format_DE($resultDate[$key], "yyyy-MM-dd", "MMMM yyyy"));

        $testMonth = $testDateArray[0];

        $testYear = $testDateArray[1];

        $sheet = new Worksheet($spreadsheet, $key);

        $spreadsheet->addSheet($sheet, $sheetCounter);

        $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName($key));

        $spreadsheet->setActiveSheetIndex($sheetIndex);

        $activeWorksheet = $spreadsheet->getActiveSheet();

        $activeWorksheet->setCellValue("A1", $testMonth);

        $activeWorksheet->setCellValue("B1", $testYear);

        $surveyResultCounter = 2;

        foreach($value as $surveyResult) {

            $surveyResultArray = json_decode($surveyResult);

            $lastKey = array_pop($surveyResultArray);

            $newSurveyResultArray = [$lastKey, ...$surveyResultArray];

            $columnLetter = Excel::getCharacter($newSurveyResultArray);

            foreach($columnLetter as $columnKey => $columnValue){

                $activeWorksheet->setCellValue($columnKey . $surveyResultCounter, $columnValue);

                if(strlen($columnValue) < 10) {

                    $cell = $sheet->getCell($columnKey . $surveyResultCounter);

                    $style = $cell->getStyle();
                    $alignment = $style->getAlignment();
                    $alignment->setHorizontal('center');
                }
            }

            $surveyResultCounter++;
        }

        $sheetCounter++;
    }

    if($requestType === "test") {

        echo "hallo";

        /*
        echo "<pre>";

        var_dump($postKeys);

        var_dump($resultDate);

        var_dump($resultDB);
        */

    } else {


        $res = $api->createResponse();

        $res->setHeader("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        $res->setHeader('Content-Disposition: attachment;filename="Erhebung.xlsx"');
        $res->setHeader("Cache-Control: max-age=0");

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        // $writer = new Xlsx($spreadsheet);

        $writer->save('php://output');
    }

} else {

    $api->createJSONResponse(["status" => "Your beer is stale - go home!"])->run(403);
}