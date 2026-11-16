<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Document {

    use AllowDynamicProperties;

    #[AllowDynamicProperties]
    class HTMLetcElementInfo {

        public ?string $tagName = null;

        public ?string $domPath = null;

        public ?string $domParentPath = null;

        public ?string $domParentParentPath = null;

        public ?string $namespacePath = null;

        public ?string $textPath = null;

        public function __set(string $name, mixed $value): void {

            $this->$name = $value;
        }
    }
}