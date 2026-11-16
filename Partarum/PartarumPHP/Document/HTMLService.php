<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Document {

    use Generator;

    class HTMLService {

        public static function createID(string $item, int $countID): Generator {

            for ($i = 0; $i <= $countID; $i++) {
                yield $item . "_" . $i;
            }
        }

        public static function getFolder(string $parentPath): Generator {

            $lastPart = strrchr($parentPath, DIRECTORY_SEPARATOR);
            yield $lastPart !== false ? substr($lastPart, 1) : $parentPath;
        }

        public function sortParentPaths(iterable $object): array {

            $numberArray = [];
            $pathArray = [];
            $resultArray = [];
            $items = [];

            foreach ($object as $path => $val) {
                $pathStr = (string)$path;
                $items[$pathStr] = $val;
                $numberArray[] = substr_count($pathStr, DIRECTORY_SEPARATOR);
                $pathArray[] = $pathStr;
            }

            natsort($numberArray);

            foreach ($numberArray as $key => $v) {
                $resultArray[$pathArray[$key]] = $items[$pathArray[$key]];
            }

            return $resultArray;
        }
    }
}