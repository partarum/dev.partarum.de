<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Document {

    use DOMElement;
    use DOMText;

    class HTMLElement extends DOMElement {

        public array $parent = [];

        public array $children = [];

        public function __set(string $name, mixed $value): void {

            switch ($name) {
                case "text":
                    $this->appendChild(new DOMText((string)$value));
                    break;
                default:
                    new HTMLAttribute([$name => $value], $this);
            }
        }

        public function __construct(object|string $node, mixed $parent = null) {

            if ((is_object($node)) && isset($node->element) && ($node->element !== null)) {
                parent::__construct((string)$node->element);

                if ($parent !== null) {
                    $this->attachToParent($parent);

                    if (isset($node->text) && ($node->text !== null)) {
                        $this->appendChild(new DOMText((string)$node->text));
                    }

                    new HTMLAttribute($node, $this);
                }
            } elseif (is_string($node) && $node !== "") {
                parent::__construct($node);

                if ($parent !== null) {
                    $this->attachToParent($parent);
                }
            }
        }

        protected function attachToParent(mixed $parent): void {

            if ($parent instanceof DOMElement) {
                $this->parent[] = $parent;
                $parent->appendChild($this);
                if ($parent instanceof self) {
                    $parent->children[] = $this;
                }
                return;
            }

            if ($parent instanceof HTMLSeries) {
                $target = $parent->elements[0] ?? ($parent->parent[0] ?? null);
                if ($target instanceof DOMElement) {
                    $this->parent[] = $target;
                    $target->appendChild($this);
                    if ($target instanceof self) {
                        $target->children[] = $this;
                    }
                }
            }
        }
    }
}
