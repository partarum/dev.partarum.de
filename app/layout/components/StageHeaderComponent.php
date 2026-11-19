<?php
declare(strict_types=1);

namespace app\layout\components;

use app\core\Contracts\RenderableInterface;
use app\core\Traits\FeatHtmlEscape;

class StageHeaderComponent implements RenderableInterface {

    use FeatHtmlEscape;

    public function __construct(
        private readonly string $title,
        private readonly string $datetime,
        private readonly string $displayDate,
        private readonly bool $wrapInArticle = true,
        private readonly string $headerClass = "pfm-onboarding-header ie-header",
        private readonly ?string $headerId = null
    ) {}

    public function render(): string {

        $escapedTitle = $this->escape($this->title);
        $escapedDatetime = $this->escape($this->datetime);
        $escapedDisplayDate = $this->escape($this->displayDate);
        $escapedClass = $this->escape($this->headerClass);
        $idAttr = ($this->headerId !== null && $this->headerId !== "")
            ? 'id="' . $this->escape($this->headerId) . '" '
            : "";

        if ($this->wrapInArticle) {
            return <<<HTML
<header {$idAttr}class="{$escapedClass}">
    <article>
        <h1>{$escapedTitle}</h1>
        <time datetime="{$escapedDatetime}">{$escapedDisplayDate}</time>
    </article>
</header>
HTML;
        }

        return <<<HTML
<header {$idAttr}class="{$escapedClass}">
    <h1>{$escapedTitle}</h1>
    <time datetime="{$escapedDatetime}">{$escapedDisplayDate}</time>
</header>
HTML;
    }
}
