<?php
namespace Partarum\PartarumPHP\Document {

    class HTMLetcElementInfo {

        public $tagName;

        public $domPath;

        public $domParentPath;

        public $domParentParentPath;

        public $namespacePath;

        public $textPath;

        public function __set($name, $value){

            $this->$name = $value;
        }

    }
}