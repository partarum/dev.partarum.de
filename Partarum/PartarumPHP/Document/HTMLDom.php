<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Document {

    use AllowDynamicProperties;

    #[AllowDynamicProperties]
    class HTMLDom {

        public static array $path = [];

        public static string $html = "";

        private array $properties = [];

        public function __construct() {

            self::$path["html"] = "surface/html";
        }

        public function getPath(string $name): mixed {

            return $this->properties[$name] ?? null;
        }

        public function __set(string $name, mixed $value): void {

            $this->properties[$name] = $value;
        }

        public function __get(string $name): mixed {

            return $this->properties[$name] ?? null;
        }

        public function __isset(string $name): bool {

            return isset($this->properties[$name]);
        }
    }
}
