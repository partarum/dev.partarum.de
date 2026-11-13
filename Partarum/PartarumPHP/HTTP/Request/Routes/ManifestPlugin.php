<?php
namespace Partarum\PartarumPHP\HTTP\Request\Routes {

    class ManifestPlugin{

        public const PATH = [

        ];

        public static function getPath($needle) : string {

            $key = strtolower($needle);

            return self::PATH[$key];
        }
    }
}