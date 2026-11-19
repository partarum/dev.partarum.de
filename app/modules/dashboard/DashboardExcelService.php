<?php
declare(strict_types=1);

namespace app\modules\dashboard;

use app\core\Enums\SurveyTopic;
use app\models\Befragung\SurveyDashboardModel;
use app\models\Befragung\SurveySubmissionModel;
use app\models\Befragung\SurveyTokenModel;
use Partarum\PartarumPHP\Helper\Date\DateFormatter;
use Partarum\PartarumPHP\Helper\Spreadsheet\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DashboardExcelService {

    /**
     * Erstellt die Excel-Arbeitsmappe für die eingegangenen Befragungen (`Erhebung.xlsx`).
     *
     * @param array<string, mixed> $selectedTopics
     */
    public function buildSurveySpreadsheet(
        SurveyDashboardModel $dashboardModel,
        SurveySubmissionModel $submissionModel,
        mixed $allFlag,
        array $selectedTopics,
        ?string $referenceDate = null
    ): Spreadsheet {

        $invalidValues = ["", null];
        $includeAll = !in_array($allFlag, $invalidValues, true);

        $spreadsheet = new Spreadsheet();
        $sheetCounter = 0;

        foreach (SurveyTopic::cases() as $topic) {
            $key = $topic->value;
            $topicVal = $selectedTopics[$key] ?? $selectedTopics[strtolower($key)] ?? null;

            if (!$includeAll && in_array($topicVal, $invalidValues, true)) {
                continue;
            }

            $rows = iterator_to_array($submissionModel->yieldExcelRowsByTopic($topic));
            if ($rows === []) {
                continue;
            }

            $startDate = $dashboardModel->getStartDate($topic);
            $rawDate = ($startDate !== null && $startDate !== "")
                ? $startDate
                : ($referenceDate ?? (string)DateFormatter::format_DE(time(), null, "yyyy-MM-dd"));

            $formattedDate = (string)DateFormatter::format_DE($rawDate, "yyyy-MM-dd", "MMMM yyyy");
            $dateParts = explode(" ", $formattedDate);

            $monthName = $dateParts[0] ?? "";
            $yearNumber = $dateParts[1] ?? "";

            $sheet = new Worksheet($spreadsheet, $key);
            $spreadsheet->addSheet($sheet, $sheetCounter);

            $targetSheet = $spreadsheet->getSheetByName($key);
            if ($targetSheet !== null) {
                $sheetIndex = $spreadsheet->getIndex($targetSheet);
                $spreadsheet->setActiveSheetIndex($sheetIndex);
            }

            $activeWorksheet = $spreadsheet->getActiveSheet();
            $activeWorksheet->setCellValue("A1", $monthName);
            $activeWorksheet->setCellValue("B1", $yearNumber);

            $rowCursor = 2;

            foreach ($rows as $rowValues) {
                $columnLetterMap = Excel::getCharacter($rowValues);

                foreach ($columnLetterMap as $columnKey => $columnValue) {
                    $cellValue = is_scalar($columnValue) || $columnValue === null
                        ? (string)$columnValue
                        : (json_encode($columnValue) ?: "");

                    $activeWorksheet->setCellValue($columnKey . $rowCursor, $cellValue);

                    if (strlen($cellValue) < 10) {
                        $sheet
                            ->getCell($columnKey . $rowCursor)
                            ->getStyle()
                            ->getAlignment()
                            ->setHorizontal("center");
                    }
                }

                $rowCursor++;
            }

            $sheetCounter++;
        }

        return $spreadsheet;
    }

    /**
     * Erstellt die formatierte Excel-Arbeitsmappe für die Befragungs-Tokens (`example.xlsx`).
     */
    public function buildTokenSpreadsheet(SurveyTokenModel $tokenModel): Spreadsheet {

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();

        $activeWorksheet->setCellValue("B2", "Befragung");
        $activeWorksheet->setCellValue("C2", "Token");

        foreach ($tokenModel->yieldAllTokens() as $index => $tokenRow) {
            $rowIndex = $index + 3;
            $activeWorksheet->setCellValue("B" . $rowIndex, $tokenRow["name"]);
            $activeWorksheet->setCellValue("C" . $rowIndex, $tokenRow["value"]);
        }

        $activeWorksheet->getColumnDimension("B")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("C")->setAutoSize(true);

        $lastColumn = $activeWorksheet->getHighestColumn();
        $lastRow = max(3, $activeWorksheet->getHighestRow());

        $tableRange = "B2:" . $lastColumn . $lastRow;
        $tableHeaderRange = "B2:C2";
        $tableValueTopRange = "B3:C3";
        $tableValueBottomRange = "B" . $lastRow . ":C" . $lastRow;
        $tableValueRangeA = "B3:B" . $lastRow;
        $tableValueRangeB = "C3:C" . $lastRow;

        $activeWorksheet->getStyle($tableRange)->applyFromArray([
            "borders" => [
                "left" => ["borderStyle" => Border::BORDER_MEDIUM],
                "right" => ["borderStyle" => Border::BORDER_MEDIUM],
                "top" => ["borderStyle" => Border::BORDER_MEDIUM],
                "bottom" => ["borderStyle" => Border::BORDER_MEDIUM]
            ]
        ]);

        $activeWorksheet->getStyle($tableHeaderRange)->applyFromArray([
            "borders" => [
                "top" => ["borderStyle" => Border::BORDER_MEDIUM],
                "bottom" => ["borderStyle" => Border::BORDER_THIN]
            ]
        ]);

        $activeWorksheet->getStyle($tableValueTopRange)->applyFromArray([
            "borders" => [
                "top" => ["borderStyle" => Border::BORDER_THIN]
            ]
        ]);

        $activeWorksheet->getStyle($tableValueBottomRange)->applyFromArray([
            "borders" => [
                "bottom" => ["borderStyle" => Border::BORDER_MEDIUM]
            ]
        ]);

        $activeWorksheet->getStyle($tableValueRangeA)->applyFromArray([
            "borders" => [
                "right" => ["borderStyle" => Border::BORDER_THIN]
            ]
        ]);

        $activeWorksheet->getStyle($tableValueRangeB)->applyFromArray([
            "borders" => [
                "left" => ["borderStyle" => Border::BORDER_THIN]
            ]
        ]);

        return $spreadsheet;
    }

    /**
     * Schreibt ein Spreadsheet als XLSX-Stream nach `php://output` (oder einen anderen Zielpfad).
     */
    public function streamSpreadsheet(Spreadsheet $spreadsheet, string $target = "php://output"): void {

        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save($target);
    }
}
