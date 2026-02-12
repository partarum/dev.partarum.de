<?php
error_reporting(-1);
ini_set("display_errors", "1");

session_start();

if (!class_exists('Partarum')) {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/Partarum/Partarum.php";
}

use Partarum\HTTP\API;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


$backend = new Partarum("getToken");

$api = new API();

$bearer = explode("Bearer ", $api->fromHeader("Authorization", API::REQUEST));

if($backend->isIntoCache("Bearer:" . $bearer[1])){

    $env = $backend->loadSecret("DB/db2");

    $db = $backend->createDBConnection([
        "type" => $env["DB_TYPE"],
        "host" => $env["DB_HOST"],
        "database" => $env["DB_NAME"],
        "username" => $env["DB_USER"],
        "password" => $env["DB_PW"]
    ]);

    $result = $db->select("token", ["value", "name"]);

    $spreadsheet = new Spreadsheet();

    $activeWorksheet = $spreadsheet->getActiveSheet();

    define("START_CELL_COLUMN", "B");

    define("START_CELL_ROW", "2");

    $activeWorksheet->setCellValue("B2", "Befragung");

    $activeWorksheet->setCellValue("C2", "Token");



    foreach($result as $key => $data){

        // Jede Zweite Reihe ein Leerfeld - bzgl. der Lesbarkeit


        $name = $data['name'];
        $value = $data['value'];

        $activeWorksheet->setCellValue("B" . $key + 3, $name);

        $activeWorksheet->setCellValue("C" . $key + 3, $value);
    }



    $activeWorksheet->getColumnDimension('B')->setAutoSize(true);

    $activeWorksheet->getColumnDimension('C')->setAutoSize(true);

    $lastColumn = $activeWorksheet->getHighestColumn();

    $lastRow = $activeWorksheet->getHighestRow();

    $tableRange = "B2:" . $lastColumn . $lastRow;

    $tableHeaderRange = "B2:C2";

    $tableValueTopRange = "B3:C3";

    $tableValueBottomRange = "B" . $lastRow . ":C" . $lastRow;

    $tableValueRange_A = "B3:B" . $lastRow;

    $tableValueRange_B = "C3:C" . $lastRow;

    //$activeWorksheet->setAutoFilter($tableRange);

    $tableStyle = $activeWorksheet->getStyle($tableRange);

    // Alle Border für jedes Feld werden gesetzt:
    //$tableStyle->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);

    // schnelle auf den Punkt Lösung - wenn kurz was definiert werden soll:
    // $tableStyle->getBorders()->getLeft()->setBorderStyle(Border::BORDER_MEDIUM);

    // $tableStyle->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);

    // genauere und flexiblere Lösung um auf den Style einzugehen:


    $tableStyle->applyFromArray([
        "borders" => [
            "left" => [
                "borderStyle" => Border::BORDER_MEDIUM
            ],
            "right" => [
                "borderStyle" => Border::BORDER_MEDIUM
            ],
            "top" => [
                "borderStyle" => Border::BORDER_MEDIUM
            ],
            "bottom" => [
                "borderStyle" => Border::BORDER_MEDIUM
            ]
        ]
    ]);

    $tableHeaderStyle = $activeWorksheet->getStyle($tableHeaderRange);

    $tableHeaderStyle->applyFromArray([
        "borders" => [
            "top" => [
                "borderStyle" => Border::BORDER_MEDIUM
            ],
            "bottom" => [
                "borderStyle" => Border::BORDER_THIN
            ],
        ]
    ]);

    $tableValueTopRangeStyle = $activeWorksheet->getStyle($tableValueTopRange);

    $tableValueTopRangeStyle->applyFromArray([
        "borders" => [
            "top" => [
                "borderStyle" => Border::BORDER_THIN
            ]
        ]
    ]);

    $tableValueBottomRangeStyle = $activeWorksheet->getStyle($tableValueBottomRange);

    $tableValueBottomRangeStyle->applyFromArray([
        "borders" => [
            "bottom" => [
                "borderStyle" => Border::BORDER_MEDIUM
            ]
        ]
    ]);

    $tableValueRange_A_Style = $activeWorksheet->getStyle($tableValueRange_A);

    $tableValueRange_A_Style->applyFromArray([
        "borders" => [
            "right" => [
                "borderStyle" => Border::BORDER_THIN
            ],
        ]
    ]);

    $tableValueRange_B_Style = $activeWorksheet->getStyle($tableValueRange_B);

    $tableValueRange_B_Style->applyFromArray([
        "borders" => [
            "left" => [
                "borderStyle" => Border::BORDER_THIN
            ]
        ]
    ]);

    $res = $api->createResponse();

    $res->setHeader("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    $res->setHeader('Content-Disposition: attachment;filename="example.xlsx"');
    $res->setHeader("Cache-Control: max-age=0");

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
   // $writer = new Xlsx($spreadsheet);

    $writer->save('php://output');

    //$api->createJSONResponse(["beer" => $result])->run();

} else {

    $api->createJSONResponse(["status" => "Your beer is stale - go home!"])->run(403);
}