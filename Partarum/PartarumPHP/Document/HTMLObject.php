<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Document {

    class HTMLObject {

        public HTMLhead $head;

        public HTMLheader $header;

        public HTMLmain $main;

        public HTMLfooter $footer;

        public function __construct() {

            $this->setClasses();
        }

        public function setClasses(): void {

            $this->head = new HTMLhead();
            $this->header = new HTMLheader();
            $this->main = new HTMLmain();
            $this->footer = new HTMLfooter();
        }
    }
}
