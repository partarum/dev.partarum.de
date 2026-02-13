<?php
namespace Partarum\Helper\Spreadsheet {

    class Excel {

        public static function getColumnLetter($columnIndexArray) : array {

            $characterArray = [];

            $userArray = [];

            foreach($columnIndexArray as $key => $columnIndex){

                $chArrayCount = count($characterArray);

                if($chArrayCount <= 25) {

                    // A - Z

                    $characterArray[] = chr(ord("A") + ($key));

                    $userArray[] = array_shift($columnIndexArray);
                }
            }

            return [$columnIndexArray, $characterArray, $userArray];
        }

        public static function getCharacter($userArray) : array {

            $setPointCharacter =  floor(count($userArray) / 25);

            $res = [];

            $resCharacter = [];

            $firstCharacter = "";

            for($i = 0; $i <= $setPointCharacter; $i++){

                $res[$i] = self::getColumnLetter(($i === 0) ? $userArray : $res[$i - 1][0]);

                if($i === 0) {

                    $resCharacter = [...$res[$i][1]];

                } else {

                    $firstCharacter = chr(ord("A") + ($i - 1));

                    foreach($res[$i][1] as $character) {

                        $resCharacter[] = $firstCharacter . $character;
                    }
                }
            }

            return array_combine($resCharacter, $userArray);
        }
    }
}