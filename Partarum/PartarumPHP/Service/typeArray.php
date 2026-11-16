<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Service {

    class typeArray {

        public static function is_Assoc(array $array): bool {

            return $array !== [] && (array_values($array) !== $array);
        }

        public static function every(array $array, callable $function): bool {

            foreach ($array as $value) {

                if (!$function($value)) {
                    return false;
                }
            }

            return true;
        }

    }
}