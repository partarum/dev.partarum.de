<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\HTTP\Request\Routes {

    class ManifestPartarum{

        public const PATH = [
            "partarum" => "Partarum/PartarumConfig/partarum-manifest.json",
            "partarumjs" => "Partarum/PartarumConfig/partarum-manifest.json"
        ];

        public static function getPath(array $needle) : string {

            $key = strtolower((string)($needle[1] ?? "partarum"));

            return self::PATH[$key] ?? self::PATH["partarum"];
        }
    }
}