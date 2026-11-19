<?php
declare(strict_types=1);

namespace app\views\Befragung;

use app\core\Contracts\RenderableInterface;
use app\core\Traits\FeatHtmlEscape;

class FinalStageView implements RenderableInterface {

    use FeatHtmlEscape;

    public function __construct(
        private readonly string $message = "Ihre Eingaben wurde erfolgreich gespeichert. Vielen Dank für Ihre Unterstützung!"
    ) {}

    public function render(): string {

        $escapedMessage = $this->escape($this->message);

        return <<<HTML
    <article>
        <h2>{$escapedMessage}</h2>
    </article>
HTML;
    }
}
