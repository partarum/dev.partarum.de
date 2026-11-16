<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Document {

    class HTMLFamily extends HTMLElement {

        public function setChild(object|string $name): HTMLElement {

            return new HTMLElement($name, $this);
        }

        public function setChildren(object|string $name, int $quantity = 1): HTMLSeries {

            return new HTMLSeries($name, $this, $quantity);
        }

        public function setGrandChild(object|string $name, int $parent = 0): ?HTMLElement {

            if (!isset($this->children[$parent])) {
                return null;
            }

            return new HTMLElement($name, $this->children[$parent]);
        }

        public function setGrandChildren(object|string $name, int $parent = 0, int $quantity = 1): ?HTMLSeries {

            if (!isset($this->children[$parent])) {
                return null;
            }

            return new HTMLSeries($name, $this->children[$parent], $quantity);
        }

        public function __construct(object|string $name, mixed $parent) {

            parent::__construct($name, $parent);
        }
    }
}