<?php
declare(strict_types=1);

namespace app\views\Befragung;

use app\core\Contracts\RenderableInterface;
use app\core\Enums\SurveyTopic;
use app\core\Traits\FeatHtmlEscape;

class InitialStageView implements RenderableInterface {

    use FeatHtmlEscape;

    public function __construct(
        private readonly SurveyTopic $topic,
        private readonly string $redirectUrl
    ) {}

    public function render(): string {

        $escapedUrl = $this->escape($this->redirectUrl);

        $kvhInstructions = ($this->topic === SurveyTopic::KVH)
            ? <<<HTML

    <article>
        <h2>Bitte füllen Sie die umrahmten Eingabefelder mit den Daten Ihres Unternehmens aus!</h2>
        <ul>
            <li>Dargestellt werden sollen Aspekte, die für möglichst alle Hersteller relevant sind</li>
            <li>Hauptaugenmerk liegt auf volumenmäßig wichtige Sorten und übliche Marktusancen</li>
            <li>Besonderheiten oder Kleinstmengen sollten außen vor bleiben oder im Anmerkungsfeld erwähnt werden</li>
        </ul>
    </article>
HTML
            : "";

        return <<<HTML
    <a id="startButton" href="{$escapedUrl}"> Befragung starten </a>{$kvhInstructions}
HTML;
    }
}
