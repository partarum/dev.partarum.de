<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Service {

    use Partarum\PartarumPHP\Cache\WorkerMap;
    use Partarum\PartarumPHP\Service\Binary\FlagWorker;
    use WeakMap;

    // TODO Abändern - eigentlich ist das eine große globale Storage - Klasse - auch eine Fabrik, aber auch nur eine Ableitung !!!!!!

    class Factory {

        private static ?WeakMap $map = null;

        private static ?WorkerMap $workerMap = null;

        private static int $counter = 0;

        private static function status(): void {

            (!isset(self::$map)) && self::setMap();
        }

        public static function addWorker(): object {

            return self::createWorker();
        }

        public static function createWorker(): object {

            self::status();

            self::$counter++;
            $workerID = str_replace([".", " "], "_", "FlagWorker_" . microtime());

            $worker = self::$workerMap->setWorker($workerID, FlagWorker::class);

            self::$map->offsetSet($worker, $workerID);

            return $worker;
        }

        public static function checkWorker(?object $worker = null): bool {

            self::status();
            return $worker !== null && self::$map->offsetExists($worker);
        }

        public static function deleteWorker(?object $worker = null): void {

            if ($worker !== null && self::$map !== null && self::$map->offsetExists($worker)) {
                self::$map->offsetUnset($worker);
            }
        }

        public static function setMap(): void {

            self::$map = new WeakMap();

            self::$workerMap = new WorkerMap();
        }
    }
}
