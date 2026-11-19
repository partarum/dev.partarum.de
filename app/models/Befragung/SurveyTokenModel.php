<?php
declare(strict_types=1);

namespace app\models\Befragung;

use app\core\Contracts\ModelInterface;
use app\core\Enums\SurveyTopic;
use app\core\Traits\FeatModelDatabase;
use Generator;
use Medoo\Medoo;
use Partarum\PartarumPHP\Security\Token\JWT;

class SurveyTokenModel implements ModelInterface {

    use FeatModelDatabase;

    public function __construct(Medoo $db) {

        $this->db = $db;
    }

    /**
     * Erzeugt und speichert JWT-Tokens für die ausgewählten Topics in der Tabelle `token`.
     *
     * @param array<string, mixed> $selectedTopics
     * @return array{
     *     token: array<string, string>,
     *     insert: array<int, string>,
     *     update: array<int, string>
     * }
     */
    public function upsertTokens(mixed $allFlag, array $selectedTopics, string $secretKey): array {

        $result = [];

        foreach (SurveyTopic::cases() as $topic) {
            $lowerKey = strtolower($topic->value);
            $topicVal = $selectedTopics[$lowerKey] ?? $selectedTopics[$topic->value] ?? null;

            if ($allFlag !== null || $topicVal !== null) {
                $jwt = new JWT();
                $jwt->setSecretKey($secretKey);
                $jwt->setPayload([$lowerKey]);

                $result[$lowerKey] = $jwt->getToken();
            }
        }

        $insertArray = [];
        $insertArrayResult = [];
        $updateArrayResult = [];

        foreach ($result as $key => $value) {
            if ($this->db->has("token", ["name" => $key])) {
                $this->db->update("token", [
                    "value" => $value
                ], [
                    "name" => $key
                ]);

                $updateArrayResult[] = $key;
            } else {
                $insertArray[] = [
                    "name" => $key,
                    "value" => $value
                ];

                $insertArrayResult[] = $key;
            }
        }

        if ($insertArray !== []) {
            $this->db->insert("token", $insertArray);
        }

        return [
            "token" => $result,
            "insert" => $insertArrayResult,
            "update" => $updateArrayResult
        ];
    }

    /**
     * Generator über alle Einträge (`name`, `value`) der Tabelle `token`.
     *
     * @return Generator<int, array{name: string, value: string}>
     */
    public function yieldAllTokens(): Generator {

        $rows = $this->db->select("token", ["value", "name"]);

        if (!is_array($rows)) {
            return;
        }

        foreach ($rows as $index => $row) {
            if (!is_array($row)) {
                continue;
            }

            yield (int)$index => [
                "name" => (string)($row["name"] ?? ""),
                "value" => (string)($row["value"] ?? "")
            ];
        }
    }
}
