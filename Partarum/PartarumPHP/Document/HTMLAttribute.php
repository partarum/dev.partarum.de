<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Document {

    use DOMElement;

    class HTMLAttribute {

        public function __construct(iterable|object $nodeObject, DOMElement $element) {

            $items = is_object($nodeObject) && !is_iterable($nodeObject)
                ? get_object_vars($nodeObject)
                : $nodeObject;

            foreach ($items as $attribute => $value) {
                if (!in_array((string)$attribute, ["event", "element", "text"], true) && ($value !== null)) {
                    $element->setAttribute((string)$attribute, (string)$value);
                }
            }
        }
    }
}
