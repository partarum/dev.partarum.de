<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Document {

    use AllowDynamicProperties;
    use Partarum\PartarumPHP\Traits\Document\FeatHTMLAttributes;

    #[AllowDynamicProperties]
    class NodeObject {

        use FeatHTMLAttributes;

        public ?string $element = null;

        public mixed $event = null;

        public ?string $text = null;

        public function __construct(?string $el = null) {

            if ($el !== null) {
                $this->element = $el;
            }
        }
    }
}