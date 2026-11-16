<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Document {

    use DOMElement;
    use DOMText;

    class HTMLText {

        public function __construct(string|int|float $text, DOMElement $element) {

            $element->appendChild(new DOMText((string)$text));
        }
    }
}