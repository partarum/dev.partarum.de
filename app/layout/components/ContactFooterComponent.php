<?php
declare(strict_types=1);

namespace app\layout\components;

use app\core\Contracts\RenderableInterface;
use app\core\Traits\FeatHtmlEscape;

class ContactFooterComponent implements RenderableInterface {

    use FeatHtmlEscape;

    public function __construct(
        private readonly ?string $partnerString = null,
        private readonly bool $showIeNotice = false,
        private readonly string $footerClass = "pfm-onboarding-footer ie-footer",
        private readonly string $footerId = "footer"
    ) {}

    public function render(): string {

        $escapedClass = $this->escape($this->footerClass);
        $escapedId = $this->escape($this->footerId);

        $partnerHeadline = ($this->partnerString !== null && $this->partnerString !== "")
            ? "        <h3>" . $this->escape($this->partnerString) . "</h3>\n"
            : "";

        $asideContent = $this->showIeNotice
            ? <<<HTML
    <aside>
        <h3>Bemerkung:</h3>
        <p id="ieInfoText">
            Bitte beachten Sie, dass der Internet Explorer als Webbrowser veraltet ist und viele moderne Webtechnologien
            nicht vollständig unterstützt.
            Dies kann zu Einschränkungen und Kompatibilitätsproblemen führen, insbesondere bei der Verwendung von
            aktuellen Webseiten und Anwendungen.
            Aus diesem Grund empfehle ich Ihnen, alternative Browser zu verwenden, die eine bessere Unterstützung für
            aktuelle Webstandards bieten.
            Beliebte und moderne Browser wie Google Chrome, Mozilla Firefox, Microsoft Edge oder Safari bieten eine
            optimale Erfahrung beim Surfen im Internet und ermöglichen Ihnen den Zugriff auf alle Funktionen und Inhalte
            von Websites.
        </p>
    </aside>
HTML
            : "    <aside>\n    </aside>";

        return <<<HTML
<footer id="{$escapedId}" class="{$escapedClass}">
    <article>
{$partnerHeadline}        <section>
            <h4>Kontakt</h4>
            <div class="pfm-contact-box">
                <div class="pfm-col-1">
                    <ul>
                        <li>Dr. Franz-Josef Lückge</li>
                        <li>Forst Holz Markt Consulting</li>
                        <li>Bachstraße 7</li>
                        <li>79235 Vogtsburg</li>
                        <li><br></li>
                        <li>+49 7662 226 49 90</li>
                        <li>FJ@Lueckge.de</li>
                    </ul>
                </div>
            </div>
        </section>
    </article>
{$asideContent}
</footer>
HTML;
    }
}
