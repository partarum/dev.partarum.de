<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Traits\Filesystem {

    use DateTime;

    trait FeatFilesystem {

        public static array $fileFolder = [
            "folder" => [
                "path"             => [],
                "name"             => [],
                "accessTime"       => [
                    "timestamp" => [],
                    "dateTime"  => []
                ],
                "modificationTime" => [
                    "timestamp" => [],
                    "dateTime"  => []
                ]
            ],
            "file"   => [
                "path"             => [],
                "name"             => [],
                "accessTime"       => [],
                "modificationTime" => []
            ]
        ];

        public static function setFileFolder(array $iterator, string $type, ?string $path, int $pathType): void {

            $pathKey = $path ?? "";
            self::$fileFolder[$pathKey][$type]["path"][]                          = $iterator[0] ?? "";
            self::$fileFolder[$pathKey][$type]["name"][]                          = $iterator[1] ?? "";
            self::$fileFolder[$pathKey][$type]["accessTime"]["timestamp"][]       = $iterator[2] ?? 0;
            self::$fileFolder[$pathKey][$type]["accessTime"]["dateTime"][]        = self::setDateTime($iterator[2] ?? 0);
            self::$fileFolder[$pathKey][$type]["modificationTime"]["timestamp"][] = $iterator[3] ?? 0;
            self::$fileFolder[$pathKey][$type]["modificationTime"]["dateTime"][]  = self::setDateTime($iterator[3] ?? 0);
        }

        public static function setDateTime(int | string $timestamp): string {

            $dateTime = DateTime::createFromFormat("U", (string)$timestamp);
            return $dateTime !== false ? $dateTime->format("d m Y H:i:s") : "";
        }
    }
}