<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\HTTP\Header\Entity {

    use Partarum\PartarumPHP\HTTP\Header\HeaderField;

    class ContentType implements HeaderField {

        public const BASE = "Content-Type: ";

        public const JSON = "application/json";

        public const TEXT = "text/plain";

        public const HTML = "text/html";

        public const XML = "text/xml";

        public static function getOutput(string | int $field): string | int | bool {

            $s = strtoupper((string)$field);
            $constName = self::class . "::" . $s;

            return defined($constName) ? self::BASE . (string)constant($constName) : false;
        }

    }
}