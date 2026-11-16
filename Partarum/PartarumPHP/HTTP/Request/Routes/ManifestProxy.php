<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\HTTP\Request\Routes {

    class ManifestProxy{

        public const PATH = [
            "assets" => "config/assets-manifest.json",
            "surface" => "config/surface-manifest.json",
            "text" => "config/text-manifest.json",
            "fetch" => "config/fetch-manifest.json",
            "app" => "config/routes-manifest.json",
            "error" => "config/error-manifest.json"
        ];

        public static function getPath(array | string $needle, null | object $manifest = null) : string {

            /*
             *  Es benötigt hier etwas Ordnung !
             *
             *  Was wird benötigt um den Proxy unbestimmt auszuführen - so das der Proxy die Hand auf hat.
             */

            $target = is_array($needle) ? ($needle[1] ?? "app") : (string)$needle;

            $key = strtolower($target);

            return self::PATH[$key] ?? self::PATH["app"];
        }
    }
}