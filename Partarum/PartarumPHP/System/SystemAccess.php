<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\System;

use Exception;
use Predis\Client;

class SystemAccess {

    public const STOP_AREA = [
        "bin",
        "boot",
        "dev",
        "etc"
    ];

    /**
     * Wechselt eine Verzeichnisebene nach oben, solange Zugriffsrechte / open_basedir dies erlauben.
     *
     * @return bool
     */
    public static function goDown(): bool {

        $dir = false;

        try {
            $dir = @chdir("..");
        } catch(Exception $err) {

        }

        return $dir;
    }

    /**
     * Ermittelt einmalig beim Systemstart, wie weit der PHP-User im Dateisystem nach oben sieht,
     * auf welche Ordner er Zugriff hat und in welchem User-Kontext der Prozess läuft.
     *
     * @param string|null $startDir
     * @param string|null $referenceFile
     * @return array
     */
    public static function collect(?string $startDir = null, ?string $referenceFile = null): array {

        $nowDir = $startDir ?? (getcwd() ?: (defined("ROOT") ? ROOT : __DIR__));
        $refFile = $referenceFile ?? __FILE__;

        $scanArray = [];
        $lastScan = null;

        while(self::goDown() === true) {

            $currentDir = getcwd();

            if($currentDir === false) {
                break;
            }

            $scan = @scandir($currentDir);

            if(!is_array($scan)) {
                break;
            }

            if(array_intersect(self::STOP_AREA, $scan) === self::STOP_AREA) {
                break;
            }

            if($lastScan !== $scan) {
                $ownerUid = @fileowner($currentDir);
                $groupGid = @filegroup($currentDir);

                $scanArray[$currentDir] = [
                    "owner" => ($ownerUid !== false && function_exists("posix_getpwuid")) ? posix_getpwuid($ownerUid) : $ownerUid,
                    "group" => ($groupGid !== false && function_exists("posix_getgrgid")) ? posix_getgrgid($groupGid) : $groupGid,
                    "entries" => $scan
                ];
                $lastScan = $scan;
            } else {
                break;
            }
        }

        @chdir($nowDir);

        $fileGroupGid = @filegroup($refFile);

        $userContext = [
            "currentUser" => get_current_user(),
            "uid" => getmyuid(),
            "gid" => getmygid(),
            "phpInfo" => (function_exists("posix_getpwuid") && getmyuid() !== false) ? posix_getpwuid(getmyuid()) : null,
            "processUser" => function_exists("posix_geteuid") ? posix_getpwuid(posix_geteuid()) : null,
            "fileGroup" => (function_exists("posix_getpwuid") && $fileGroupGid !== false) ? posix_getpwuid($fileGroupGid) : null
        ];

        return [
            "checkedAt" => date("Y-m-d H:i:s"),
            "startDir" => $nowDir,
            "user" => $userContext,
            "scanArray" => $scanArray
        ];
    }

    /**
     * Prüft, ob der System-Access-Eintrag in Redis existiert, und legt ihn beim Erststart an.
     *
     * @param Client $redis
     * @param string $partarumKey
     * @param string|null $referenceFile
     * @return void
     */
    public static function init(Client $redis, string $partarumKey, ?string $referenceFile = null): void {

        $redisKey = $partarumKey . ":system:access";

        if(!$redis->exists($redisKey)) {
            $accessData = self::collect(null, $referenceFile);
            $redis->set($redisKey, json_encode($accessData));
        }
    }
}
