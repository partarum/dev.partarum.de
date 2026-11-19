<?php
declare(strict_types=1);

namespace app\models\Befragung;

use app\core\Contracts\ModelInterface;
use app\core\Enums\SurveyTopic;
use app\core\Traits\FeatModelDatabase;
use app\src\Befragung\Surface;
use Generator;
use Medoo\Medoo;

class SurveyDashboardModel implements ModelInterface {

    use FeatModelDatabase;

    public function __construct(Medoo $db) {

        $this->db = $db;
    }

    public function getStartDate(SurveyTopic $topic): ?string {

        $dbDate = $this->db->get("survey_dashboard", "survey_start", ["topic" => $topic->value]);

        return is_string($dbDate) && $dbDate !== "" ? $dbDate : null;
    }

    /**
     * @return array{date: string, datetime: string, displayDate: string}|null
     */
    public function getFormattedDateInfo(SurveyTopic $topic): ?array {

        $startDate = $this->getStartDate($topic);

        return Surface::getDate($startDate);
    }

    public function getTopicRow(SurveyTopic $topic): ?array {

        $row = $this->db->get("survey_dashboard", [
            "topic",
            "survey_start",
            "survey_stop",
            "survey_has_been_set",
            "survey_has_been_stopped",
            "survey_status",
            "survey_diff",
            "survey_max_diff"
        ], [
            "topic" => $topic->value
        ]);

        return is_array($row) ? $row : null;
    }

    /**
     * Generator über alle SurveyTopic-Einträge in survey_dashboard.
     *
     * @return Generator<string, array>
     */
    public function yieldAllTopics(): Generator {

        foreach (SurveyTopic::cases() as $topic) {
            $row = $this->getTopicRow($topic);
            if ($row !== null) {
                yield $topic->value => $row;
            }
        }
    }
}
