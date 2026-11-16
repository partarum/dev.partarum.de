<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Helper\Spreadsheet {

    class Excel {

        public static function getColumnLetter(array $columnIndexArray): array {

            $characterArray = [];
            $userArray = [];
            $values = array_values($columnIndexArray);
            $limit = min(count($values), 26);

            for ($index = 0; $index < $limit; $index++) {
                // A - Z (26 columns)
                $characterArray[] = chr(ord("A") + $index);
                $userArray[] = $values[$index];
            }

            $remaining = array_slice($values, $limit);

            return [$remaining, $characterArray, $userArray];
        }

        public static function getCharacter(array $userArray): array {

            $values = array_values($userArray);
            $count = count($values);

            if ($count === 0) {
                return [];
            }

            $chunks = (int)ceil($count / 26);
            $res = [];
            $resCharacter = [];

            for ($i = 0; $i < $chunks; $i++) {

                $res[$i] = self::getColumnLetter(($i === 0) ? $values : $res[$i - 1][0]);

                if ($i === 0) {
                    $resCharacter = [...$res[$i][1]];
                } else {
                    $firstCharacter = chr(ord("A") + ($i - 1));

                    foreach ($res[$i][1] as $character) {
                        $resCharacter[] = $firstCharacter . $character;
                    }
                }
            }

            return array_combine($resCharacter, $values);
        }
    }
}