<?php
declare(strict_types=1);

namespace app\models\Befragung;

use app\core\Contracts\ModelInterface;
use app\core\Enums\SurveyTopic;
use app\core\Traits\FeatModelDatabase;
use Medoo\Medoo;

class SurveySubmissionModel implements ModelInterface {

    use FeatModelDatabase;

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
            "survey_excel_character_content" => json_encode($excelCharacterArray, JSON_UNESCAPED_UNICODE)
        ]);

        return true;
    }

    public function countByTopic(SurveyTopic $topic): int {

        return (int)$this->db->count("survey", [
            "topic" => $topic->value
        ]);
    }
}
