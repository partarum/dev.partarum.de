<?php
declare(strict_types=1);

namespace app\models\Befragung;

use app\core\Contracts\ModelInterface;
use app\core\Enums\SurveyTopic;
use app\core\Traits\FeatModelDatabase;
use app\src\Befragung\Surface;
use Carbon\Carbon;
use Generator;
use Medoo\Medoo;
use Partarum\PartarumPHP\Helper\Date\DateCalculator;
use Partarum\PartarumPHP\Helper\Date\DateFormatter;

class SurveyDashboardModel implements ModelInterface {

    use FeatModelDatabase;

    private const DASHBOARD_COLUMNS = [
        "survey_start",
        "survey_stop",
        "survey_has_been_set",
        "survey_has_been_stopped",
        "survey_status",
        "survey_diff",
        "survey_max_diff"
    ];

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
            ...self::DASHBOARD_COLUMNS
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

    /**
     * Generator über alle Topics: liefert die jeweiligen Startdaten (`survey_start`).
     *
     * @return Generator<string, string|null>
     */
    public function yieldStartDates(): Generator {

        foreach (SurveyTopic::cases() as $topic) {
            yield $topic->value => $this->getStartDate($topic);
        }
    }

    /**
     * Prüft und aktualisiert den Erhebungsstatus (`survey_status`, `survey_diff`) aller Topics
     * für den Endpunkt `/API/V1/dashboard/checkSurvey`.
     *
     * @return array{
     *     survey_start: array<string, string|null>,
     *     status: array<string, bool>,
     *     all: array<string, array<int, array<string, mixed>>>,
     *     displayDate: array<string, array<string, string>|string>,
     *     surveyCount: array<string, int>
     * }
     */
    public function evaluateSurveyStatuses(SurveySubmissionModel $submissionModel, ?string $referenceDate = null): array {

        $now = $referenceDate ?? (string)DateFormatter::format_DE(time(), null, "yyyy-MM-dd");

        $resultStart = [];
        $resultDisplayDate = [];
        $resultALL = [];
        $statusArray = [];
        $countArrayResult = [];

        foreach (SurveyTopic::cases() as $topic) {
            $key = $topic->value;

            $rows = $this->db->select("survey_dashboard", self::DASHBOARD_COLUMNS, [
                "topic" => $key
            ]);

            $resultALL[$key] = is_array($rows) ? $rows : [];
            $surveyStart = $resultALL[$key][0]["survey_start"] ?? null;
            $resultStart[$key] = is_string($surveyStart) && $surveyStart !== "" ? $surveyStart : null;

            $diffInDays = ($resultStart[$key] !== null)
                ? (int)DateCalculator::parse($now)->diffInDays($resultStart[$key], false)
                : null;

            $maxDiffRaw = $resultALL[$key][0]["survey_max_diff"]
                ?? $this->db->get("survey_dashboard", "survey_max_diff", ["topic" => $key]);
            $maxDiff = is_numeric($maxDiffRaw) ? (int)$maxDiffRaw : 0;

            $status = (
                $resultStart[$key] !== null
                && $diffInDays !== null
                && $diffInDays >= -$maxDiff
                && $diffInDays <= $maxDiff
            );

            $statusArray[$key] = $status;

            if ($resultStart[$key] !== null) {
                $formatted = DateFormatter::format_DE($resultStart[$key], "yyyy-MM-dd", [
                    "date" => "yyyy-MM-dd",
                    "datetime" => "yyyy-MM",
                    "displayDate" => "MMMM yyyy"
                ]);
                $resultDisplayDate[$key] = is_array($formatted) ? $formatted : [];
            } else {
                $resultDisplayDate[$key] = "0000-00-00";
            }

            $this->db->update("survey_dashboard", [
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

            $countArrayResult[$key] = $submissionModel->countByTopic($topic);
        }

        return [
            "survey_start" => $resultStart,
            "status" => $statusArray,
            "all" => $resultALL,
            "displayDate" => $resultDisplayDate,
            "surveyCount" => $countArrayResult
        ];
    }

    /**
     * Startet (oder aktualisiert) die Erhebungszeiträume für alle Topics für `/API/V1/dashboard/startSurvey`.
     *
     * @param array<string, mixed> $topicDates
     * @return array{
     *     started: array<int, string>,
     *     status: array<string, string|null>,
     *     diff: array<string, array<string, mixed>>
     * }
     */
    public function startSurveys(mixed $allDate, array $topicDates, ?string $referenceDate = null): array {

        $invalidValues = ["", null];
        $allDateNorm = (!in_array($allDate, $invalidValues, true) && is_string($allDate)) ? $allDate : null;

        $surveysArray = [];
        foreach (SurveyTopic::cases() as $topic) {
            $key = $topic->value;
            $rawVal = $topicDates[$key] ?? $topicDates[strtolower($key)] ?? null;
            if ($allDateNorm !== null) {
                $surveysArray[$key] = $allDateNorm;
            } else {
                $surveysArray[$key] = (!in_array($rawVal, $invalidValues, true) && is_string($rawVal)) ? $rawVal : null;
            }
        }

        $todayStr = $referenceDate ?? (string)DateFormatter::format_DE(time(), null, "yyyy-MM-dd");
        $updateArrayResult = [];
        $diffArray = [];

        foreach ($surveysArray as $key => $value) {
            $now = ($value !== null) ? $todayStr : null;

            $diffInDays = ($value !== null && $now !== null)
                ? (int)DateCalculator::parse($now)->diffInDays($value, false)
                : null;

            $actData = $this->db->get("survey_dashboard", [
                "survey_start",
                "survey_has_been_set",
                "survey_max_diff"
            ], [
                "topic" => $key
            ]);
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
            $stop = ($stopDateObj instanceof DateCalculator || $stopDateObj instanceof Carbon)
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

            $this->db->update("survey_dashboard", $dbUpdate, [
                "topic" => $key
            ]);

            $updateArrayResult[] = $key;
        }

        return [
            "started" => $updateArrayResult,
            "status" => iterator_to_array($this->yieldStartDates()),
            "diff" => $diffArray
        ];
    }

    /**
     * Beendet ausgewählte Erhebungen und löscht deren Datensätze in `survey` für `/API/V1/dashboard/stopSurvey`.
     *
     * @param array<string, mixed> $selectedTopics
     * @return array{
     *     stopped: array<int, string>,
     *     status: array<string, string|null>,
     *     stoppedCount: array<string, int>,
     *     deletedCount: array<string, int>
     * }
     */
    public function stopSurveys(
        mixed $allFlag,
        array $selectedTopics,
        SurveySubmissionModel $submissionModel,
        ?string $referenceDate = null
    ): array {

        $resultDB = [];

        foreach (SurveyTopic::cases() as $topic) {
            $key = $topic->value;
            $topicFlag = $selectedTopics[$key] ?? $selectedTopics[strtolower($key)] ?? null;
            if ($allFlag !== null || $topicFlag !== null) {
                $resultDB[$key] = $topic;
            }
        }

        $todayStr = $referenceDate ?? (string)DateFormatter::format_DE(time(), null, "yyyy-MM-dd");
        $updateArrayResult = [];
        $countArrayResult = [];
        $deleteArrayResult = [];

        foreach ($resultDB as $key => $topic) {
            $currentStart = $this->getStartDate($topic);
            $now = ($currentStart !== null) ? $todayStr : null;

            $countArrayResult[$key] = $submissionModel->countByTopic($topic);

            $this->db->update("survey_dashboard", [
                "survey_start" => null,
                "survey_stop" => null,
                "survey_has_been_set" => null,
                "survey_has_been_stopped" => $now,
                "survey_status" => 0,
                "survey_diff" => null
            ], [
                "topic" => $key
            ]);

            $updateArrayResult[] = $key;
            $deleteArrayResult[$key] = $submissionModel->deleteByTopic($topic);
        }

        return [
            "stopped" => $updateArrayResult,
            "status" => iterator_to_array($this->yieldStartDates()),
            "stoppedCount" => $countArrayResult,
            "deletedCount" => $deleteArrayResult
        ];
    }
}
