<?php
namespace Partarum\Helper\Date {

    use IntlDateFormatter;

    class DateFormatter {

        /**
         * @param int|string $date
         * @param string|null $datePattern
         * @param string|array $returnSchema
         * @return string|array
         */
        public static function format_DE(int | string $date, null | string $datePattern, string | array $returnSchema) :  string | array {

            $returnArray = is_array($returnSchema) ? $returnSchema : [$returnSchema];

            $formatter = new IntlDateFormatter("de_DE", IntlDateFormatter::FULL, IntlDateFormatter::NONE);

            $dateObject = null;

            if(is_int($date)) {

                $dateObject = $formatter->parse($formatter->format($date));

            } else {

                $formatter->setPattern($datePattern);

                $dateObject = $formatter->parse($date);
            }

            $resultArray = [];

            foreach($returnArray as $key => $pattern) {

                if($formatter->setPattern($pattern)) {

                        $resultArray[$key] = $formatter->format($dateObject);

                       // var_dump($formatter->getErrorMessage());
                }
            }

            return (is_array($returnSchema)) ? $resultArray : $resultArray[0];
        }
    }
}