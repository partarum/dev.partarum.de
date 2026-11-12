<?php
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

        public static function getPath($needle, $manifest) : string {

            echo "ManifestProxy: getPath:\n";
            echo "needle: " . $needle . "\n";
            echo "partarum_proxy_path: " . PARTARUM_PROXY_PATH . "\n";
            echo "<pre>";
            print_r($needle);
            print_r($manifest);
            print_r($needle);

            /*
             partarum_proxy_path: /home/users/cs23-0089-gx/www/new/Partarum/PartarumWorkspace
             */

            /*
             Array
            (
                [0] =>
                [1] => Partarum
                [2] => Workspace
            )
             */

            /*
             stdClass Object
            (
                [Partarum/Workspace] => /home/users/cs23-0089-gx/www/new/Partarum/PartarumWorkspace/config/routes-manifest.json
            )
             */

            /*
             Array
            (
                [0] =>
                [1] => Partarum
                [2] => Workspace
            )

             */

            /*
             *  Es benötigt hier etwas Ordnung !
             *
             *  Was wird benötigt um den Proxy unbestimmt auszuführen - so das der Proxy die Hand auf hat.
             */

            $key = strtolower($needle);

            return self::PATH[$key];
        }
    }
}