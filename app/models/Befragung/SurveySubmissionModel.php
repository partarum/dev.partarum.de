<?php
declare(strict_types=1);

namespace app\models\Befragung;

use app\core\Contracts\ModelInterface;
use app\core\Enums\SurveyTopic;
use app\core\Traits\FeatModelDatabase;
use Generator;
use Medoo\Medoo;
use PDOStatement;

class SurveySubmissionModel implements ModelInterface {

    use FeatModelDatabase;

    private const EXCEL_COLUMN = "survey_excel_character_content";

    public function __construct(Medoo $db) {

        $this->db = $db;
    }

    /**
     * Speichert eine ausgefüllte Befragung in der Tabelle `survey`.
     *
     * @param SurveyTopic $topic
     * @param array<string, mixed> $postData
     * @param array<int, string|int> $excelCharacterArray
     */
    public function saveSubmission(SurveyTopic $topic, array $postData, array $excelCharacterArray): bool {

        if ($postData === []) {
            return false;
        }

        $this->db->insert("survey", [
            "topic" => $topic->value,
            "date_stamp" => Medoo::raw("FROM_UNIXTIME(UNIX_TIMESTAMP())"),
            "survey_content" => json_encode($postData, JSON_UNESCAPED_UNICODE),
            self::EXCEL_COLUMN => json_encode($excelCharacterArray, JSON_UNESCAPED_UNICODE)
        ]);

        return true;
    }

    public function countByTopic(SurveyTopic $topic): int {

        return (int)$this->db->count("survey", [
            "topic" => $topic->value
        ]);
    }

    /**
     * Generator über die Anzahl eingegangener Befragungen pro Topic.
     *
     * @return Generator<string, int>
     */
    public function yieldCountsByTopic(): Generator {

        foreach (SurveyTopic::cases() as $topic) {
            yield $topic->value => $this->countByTopic($topic);
        }
    }

    /**
     * Liefert die Anzahl eingegangener Befragungen für alle Topics inklusive Gesamtsumme (`ALL`).
     *
     * @return array<string, int>
     */
    public function getCountsSummary(): array {

        $result = [];
        $total = 0;

        foreach ($this->yieldCountsByTopic() as $topicKey => $count) {
            $result[$topicKey] = $count;
            $total += $count;
        }

        $result["ALL"] = $total;

        return $result;
    }

    /**
     * Löscht alle eingegangenen Befragungen eines Topics und gibt die Anzahl gelöschter Zeilen zurück.
     */
    public function deleteByTopic(SurveyTopic $topic): int {

        $deletedResult = $this->db->delete("survey", [
            "topic" => $topic->value
        ]);

        return ($deletedResult instanceof PDOStatement) ? $deletedResult->rowCount() : 0;
    }

    /**
     * Generator, der alle dekodierten Excel-Zeilen (`survey_excel_character_content`) eines Topics liefert,
     * wobei das letzte Element (E-Mail / Zeitstempel) an den Anfang verschoben wird.
     *
     * @return Generator<int, array<int, mixed>>
     */
    public function yieldExcelRowsByTopic(SurveyTopic $topic): Generator {

        $rows = $this->db->select("survey", self::EXCEL_COLUMN, [
            "topic" => $topic->value
        ]);

        if (!is_array($rows)) {
            return;
        }

        foreach ($rows as $surveyResult) {
            if (!is_string($surveyResult) || $surveyResult === "") {
                continue;
            }

            $surveyResultArray = json_decode($surveyResult, true);

            if (!is_array($surveyResultArray) || $surveyResultArray === []) {
                continue;
            }

            $lastKey = array_pop($surveyResultArray);
            yield [$lastKey, ...$surveyResultArray];
        }
    }
}
