<?php
namespace Partarum\PartarumPHP\System\Memory {

    interface SharedCacheInterface {

        public static function add($data);

        public static function get($data);

        public static function has($data);
    }
}