<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Config {

    class GlobalConfig {

        public static ?string $theme = null;

        public static int $ses = 0;

        public static ?string $style = null;

        public static int $presentation = 1;

        public static int $public = 1;

        public static int $private = 0;

        public static bool $wrapper = false;

        public static function setTheme(?string $theme): void {

            self::$theme = $theme;
        }

        public static function setSes(int $ses): void {

            self::$ses = $ses;
        }

        public static function setStyle(?string $style): void {

            self::$style = $style;
        }

        public static function setPresentation(int $presentation): void {

            self::$presentation = $presentation;
        }

        public static function setPrivate(int $private): void {

            self::$private = $private;
        }

        public static function setPublic(int $public): void {

            self::$public = $public;
        }

        public static function setWrapper(bool $wrapper): void {
            self::$wrapper = $wrapper;
        }

    }
}
