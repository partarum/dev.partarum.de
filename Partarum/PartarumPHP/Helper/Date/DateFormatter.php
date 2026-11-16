<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Helper\Date {

    use IntlDateFormatter;

    class DateFormatter {

        /**
         * @param int|string $date
         * @param string|null $datePattern
         * @param string|array $returnSchema
         * @return string|array
         */
        public static function format_DE(int | string $date, null | string $datePattern, string | array $returnSchema): string | array {

            $returnArray = is_array($returnSchema) ? $returnSchema : [$returnSchema];

            $formatter = new IntlDateFormatter("de_DE", IntlDateFormatter::FULL, IntlDateFormatter::NONE);

            if (is_int($date)) {
                $formatted = $formatter->format($date);
                $dateObject = is_string($formatted) ? $formatter->parse($formatted) : false;
            } else {
                if ($datePattern !== null) {
                    $formatter->setPattern($datePattern);
                }
                $dateObject = $formatter->parse($date);
            }

            $resultArray = [];

            foreach ($returnArray as $key => $pattern) {
                if ($dateObject !== false && $dateObject !== null && $formatter->setPattern((string)$pattern)) {
                    $formattedValue = $formatter->format($dateObject);
                    $resultArray[$key] = is_string($formattedValue) ? $formattedValue : "";
                } else {
                    $resultArray[$key] = "";
                }
            }

            if (is_array($returnSchema)) {
                return $resultArray;
            }

            $firstKey = array_key_first($resultArray);
            return $firstKey !== null ? (string)$resultArray[$firstKey] : "";
        }
    }
}