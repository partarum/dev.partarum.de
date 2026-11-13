<?php
namespace Partarum\PartarumPHP\Document {

    use Partarum\PartarumPHP\Document\{HTMLhead, HTMLheader, HTMLmain, HTMLfooter};

    class HTMLObject {

        public $head;

        public $header;

        public $main;

        public $footer;

        public function __construct(){

           $this->setClasses();
        }

        public function setClasses(){

            $this->head = new HTMLhead();
            $this->header = new HTMLheader();
            $this->main = new HTMLmain();
            $this->footer = new HTMLfooter();
        }
    }
}
