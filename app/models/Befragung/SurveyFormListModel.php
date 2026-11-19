<?php
declare(strict_types=1);

namespace app\models\Befragung;

use app\core\Contracts\ModelInterface;
use app\core\Enums\SurveyTopic;
use app\core\Traits\FeatModelDatabase;
use Generator;
use Medoo\Medoo;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class SurveyFormListModel implements ModelInterface {

    use FeatModelDatabase;

    /**
     * @var array<string, array{fields: array, areas: array, sections: array, content: array}>
     */
    private array $cache = [];

    public function __construct(Medoo $db) {

        $this->db = $db;
    }

    /**
     * Lädt und dekodiert alle JSON-Spalten aus survey_form_list für ein Topic in einer einzigen DB-Query.
     *
     * @return array{fields: array, areas: array, sections: array, content: array}
     */
    public function getConfiguration(SurveyTopic $topic): array {

        $key = $topic->value;

        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $row = $this->db->get("survey_form_list", [
            "survey_fields",
            "survey_areas",
            "survey_sections",
            "survey_content"
        ], [
            "topic" => $key
        ]);

        $rowArray = is_array($row) ? $row : [];

        $this->cache[$key] = [
            "fields" => $this->decodeColumn($rowArray["survey_fields"] ?? null),
            "areas" => $this->decodeColumn($rowArray["survey_areas"] ?? null),
            "sections" => $this->decodeColumn($rowArray["survey_sections"] ?? null),
            "content" => $this->decodeColumn($rowArray["survey_content"] ?? null)
        ];

        return $this->cache[$key];
    }

    /**
     * Generator über alle definierten Feldnamen eines Topics samt zugehörigem POST-Wert.
     *
     * @param SurveyTopic $topic
     * @param array<string, mixed> $postData
     * @return Generator<string, string|int>
     */
    public function yieldMergedPostFields(SurveyTopic $topic, array $postData): Generator {

        $config = $this->getConfiguration($topic);

        foreach ($config["fields"] as $field) {
            if (!is_string($field) || $field === "") {
                continue;
            }

            $rawVal = $postData[$field] ?? 0;
            yield $field => (is_string($rawVal) || is_int($rawVal)) ? $rawVal : (string)$rawVal;
        }
    }

    /**
     * Generator-Pipeline, die die POST-Eingaben auf die Excel-Spaltenwerte (A/B/C, Freitext, X, 0) abbildet.
     *
     * @param SurveyTopic $topic
     * @param array<string, mixed> $postData
     * @return Generator<int, string|int>
     */
    public function yieldExcelCharacters(SurveyTopic $topic, array $postData): Generator {

        $config = $this->getConfiguration($topic);
        $contentTree = $config["content"];
        $startOrd = ord("A");

        foreach ($this->yieldMergedPostFields($topic, $postData) as $postKey => $postValue) {

            $fieldArray = $this->findFieldDefinition($contentTree, $postKey);

            if (!is_array($fieldArray) || $fieldArray === []) {
                yield 0;
                continue;
            }

            $fieldCount = count($fieldArray);
            $letterMap = [];

            for ($i = 0; $i < $fieldCount; $i++) {
                $valueOfField = (string)($fieldArray[$i]["value"] ?? "");
                $mapKey = ($valueOfField !== "") ? $valueOfField : ($i + 1);
                $letterMap[$mapKey] = chr($startOrd + $i);
            }

            $valueIsSet = 0;

            foreach ($fieldArray as $valueObject) {
                if (!is_array($valueObject)) {
                    continue;
                }

                $fieldValue = (string)($valueObject["value"] ?? "");

                if ($fieldValue === "") {
                    yield ($postValue === "") ? "X" : $postValue;
                    $valueIsSet++;
                } elseif ($fieldValue === (string)$postValue && isset($letterMap[$fieldValue])) {
                    yield $letterMap[$fieldValue];
                    $valueIsSet++;
                }
            }

            if ($valueIsSet === 0) {
                yield 0;
            }
        }
    }

    /**
     * Erzeugt das vollständige Excel-Character-Array über die Generator-Pipeline.
     *
     * @param SurveyTopic $topic
     * @param array<string, mixed> $postData
     * @return array<int, string|int>
     */
    public function buildExcelCharacterArray(SurveyTopic $topic, array $postData): array {

        return iterator_to_array($this->yieldExcelCharacters($topic, $postData), false);
    }

    /**
     * Sucht rekursiv nach der Felddefinition im survey_content-Baum.
     */
    public function findFieldDefinition(array $contentTree, string $fieldKey): ?array {

        if ($contentTree === []) {
            return null;
        }

        $iterator = new RecursiveArrayIterator($contentTree);
        $recursive = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($recursive as $key => $value) {
            if ($key === $fieldKey && is_array($value)) {
                return $value;
            }
        }

        return null;
    }

    private function decodeColumn(mixed $rawJson): array {

        if (!is_string($rawJson) || trim($rawJson) === "") {
            return [];
        }

        $decoded = json_decode($rawJson, true);

        return is_array($decoded) ? $decoded : [];
    }
}
