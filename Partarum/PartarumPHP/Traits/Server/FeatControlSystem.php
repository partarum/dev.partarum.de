<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Traits\Server;

trait FeatControlSystem {

    public static ?string $slash = null;

    public static function getLocation(): string {

        $host = (string)($_SERVER["HTTP_HOST"] ?? "");

        if ($host === "localhost") {
            return "C:\\inetpub\\wwwroot\\";
        }

        if ($host === "bombis.lacerta.uberspace.de") {
            return "/var/www/virtual/bombis/html";
        }

        return defined("ROOT") ? (string)ROOT : (string)($_SERVER["DOCUMENT_ROOT"] ?? "");
    }

    public static function setLocation(string $item = "index.php"): void {

        $host = (string)($_SERVER["HTTP_HOST"] ?? "");

        if (!headers_sent()) {
            if ($host === "localhost") {
                header("Location: http://localhost/html/" . $item);
                exit();
            }

            if ($host === "bombis.lacerta.uberspace.de") {
                header("Location: http://bombis.lacerta.uberspace.de/" . $item);
                exit();
            }
        }
    }

    public static function getSystem(): string {

        $docRoot = (string)($_SERVER["DOCUMENT_ROOT"] ?? __DIR__);

        if (preg_match("/\//", $docRoot) === 1) {
            self::$slash = "/";
            return "/";
        }

        self::$slash = "\\";
        return "\\";
    }
}