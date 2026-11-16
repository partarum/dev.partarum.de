<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

use Partarum\Partarum;
use Partarum\PartarumPHP\HTTP\API;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

if (!class_exists(Partarum::class)) {
    require_once dirname(__DIR__, 3) . "/Partarum/Partarum.php";
}

$backend = new Partarum("getToken");

$api = new API();

$bearerToken = $api->fromBearerToken();

if ($bearerToken !== null && $backend->isIntoCache("Bearer:" . $bearerToken)) {

    $env = $backend->loadSecret("DB/db2");

    $db = $backend->createDBConnection([
        "type" => $env["DB_TYPE"] ?? "mysql",
        "host" => $env["DB_HOST"] ?? "localhost",
        "database" => $env["DB_NAME"] ?? "",
        "username" => $env["DB_USER"] ?? "",
        "password" => $env["DB_PW"] ?? ""
    ]);

    $rows = $db->select("token", ["value", "name"]);
    $result = is_array($rows) ? $rows : [];

    $spreadsheet = new Spreadsheet();
    $activeWorksheet = $spreadsheet->getActiveSheet();

    if (!defined("START_CELL_COLUMN")) {
        define("START_CELL_COLUMN", "B");
    }

    if (!defined("START_CELL_ROW")) {
        define("START_CELL_ROW", "2");
    }

    $activeWorksheet->setCellValue("B2", "Befragung");
    $activeWorksheet->setCellValue("C2", "Token");

    foreach ($result as $key => $data) {
        $rowIndex = (int)$key + 3;
        $name = (string)($data["name"] ?? "");
        $value = (string)($data["value"] ?? "");

        $activeWorksheet->setCellValue("B" . $rowIndex, $name);
        $activeWorksheet->setCellValue("C" . $rowIndex, $value);
    }

    $activeWorksheet->getColumnDimension("B")->setAutoSize(true);
    $activeWorksheet->getColumnDimension("C")->setAutoSize(true);

    $lastColumn = $activeWorksheet->getHighestColumn();
    $lastRow = max(3, $activeWorksheet->getHighestRow());

    $tableRange = "B2:" . $lastColumn . $lastRow;
    $tableHeaderRange = "B2:C2";
    $tableValueTopRange = "B3:C3";
    $tableValueBottomRange = "B" . $lastRow . ":C" . $lastRow;
    $tableValueRange_A = "B3:B" . $lastRow;
    $tableValueRange_B = "C3:C" . $lastRow;

    $tableStyle = $activeWorksheet->getStyle($tableRange);
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
            ]
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
            ]
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

    $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
    $writer->save("php://output");

} else {

    $api->createJSONResponse(["status" => "Your beer is stale - go home!"])->run(403);
}