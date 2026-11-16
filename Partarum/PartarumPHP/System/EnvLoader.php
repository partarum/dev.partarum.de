<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\System;

use Dotenv\Dotenv;

class EnvLoader {

    /**
     * Lädt die User-.env (falls vorhanden) in $_ENV/$_SERVER
     * und gibt die internen Partarum-Security-Variablen als Array zurück.
     *
     * @param string $root
     * @return array
     */
    public static function load(string $root = ROOT): array {

        $baseRoot = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $userEnvPath = $baseRoot . ".ghost/.user/.envBox";

        if(is_dir($userEnvPath)) {
            $dotenv = Dotenv::createImmutable($userEnvPath);
            $dotenv->safeLoad();
        }

        $securityEnvPath = $baseRoot . "Partarum/PartarumIntern/Security";

        $dotenv = Dotenv::createArrayBacked($securityEnvPath);

        return $dotenv->load();
    }

    /**
     * Lädt eine spezifische Secret-.env-Datei aus .ghost/.user/.envBox/<path> als Array.
     *
     * @param string $path
     * @param string $root
     * @return array
     */
    public static function loadSecret(string $path, string $root = ROOT): array {

        $baseRoot = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $loremPath = $baseRoot . ".ghost/.user/.envBox" . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);

        $dotEnv = Dotenv::createArrayBacked($loremPath);

        return $dotEnv->load();
    }
}
