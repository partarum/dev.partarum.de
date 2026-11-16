<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Document {

    use DOMElement;

    class HTMLSeries {

        public array $elements = [];

        private object|string $tagName;

        public ?array $parent = null;

        public ?int $countQuantity = null;

        public int $length = 0;

        public function __construct(object|string $name, mixed $parent, int $count = 1) {

            $this->tagName = $name;
            $this->parent = $this->getParentType($parent);

            $this->add($count);
        }

        public function add(int $count): void {

            if ($this->parent === null || $count <= 0) {
                $this->length = count($this->elements);
                return;
            }

            $parentCount = count($this->parent);

            for ($h = 0; $h < $parentCount; $h++) {
                for ($j = 0; $j < $count; $j++) {
                    $el = new HTMLElement($this->tagName, $this->parent[$h]);
                    $this->elements[] = $el;
                }
            }

            $this->length = count($this->elements);
        }

        public function __set(string $name, mixed $value): void {

            switch ($name) {
                case "quantity":
                    if ($this->countQuantity === null && is_numeric($value)) {
                        $parentCount = is_array($this->parent) ? count($this->parent) : 0;
                        $currentPerParent = $parentCount > 0 ? intdiv(count($this->elements), $parentCount) : 1;
                        $this->add(((int)$value) - $currentPerParent);
                        $this->countQuantity = 1;
                    }
                    break;

                case "text":
                    if (is_array($value)) {
                        $elementCount = count($this->elements);
                        for ($i = 0; $i < $elementCount; $i++) {
                            if (array_key_exists($i, $value)) {
                                new HTMLText((string)$value[$i], $this->elements[$i]);
                            }
                        }
                    } elseif (is_scalar($value)) {
                        foreach ($this->elements as $element) {
                            new HTMLText((string)$value, $element);
                        }
                    }
                    break;

                default:
                    if (is_array($value)) {
                        if (count($value) === count($this->elements)) {
                            $valueValues = array_values($value);
                            $valueCount = count($valueValues);
                            for ($i = 0; $i < $valueCount; $i++) {
                                new HTMLAttribute([$name => $valueValues[$i]], $this->elements[$i]);
                            }
                        }
                    } elseif (is_scalar($value)) {
                        foreach ($this->elements as $element) {
                            new HTMLAttribute([$name => $value], $element);
                        }
                    }
            }
        }

        public function __get(string $name): mixed {

            if (in_array($name, ["nodes", "items", "elements"], true)) {
                return $this->elements;
            }

            foreach ($this->elements as $element) {
                if ($element instanceof DOMElement && $element->getAttribute("id") === $name) {
                    return $element;
                }
            }

            return null;
        }

        public function getParentType(mixed $parent): ?array {

            if ($parent instanceof self) {
                return $parent->elements;
            }

            if ($parent instanceof DOMElement) {
                return [$parent];
            }

            if (is_array($parent)) {
                if ($parent === []) {
                    return [];
                }

                $first = reset($parent);
                if ($first instanceof self) {
                    return $first->elements;
                }

                if ($first instanceof DOMElement) {
                    return array_values($parent);
                }

                return array_values($parent);
            }

            return null;
        }
    }
}
