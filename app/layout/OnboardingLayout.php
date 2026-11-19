<?php
declare(strict_types=1);

namespace app\layout;

use app\core\Contracts\RenderableInterface;
use app\core\Traits\FeatHtmlEscape;
use app\layout\components\ContactFooterComponent;
use app\layout\components\HeadComponent;
use app\layout\components\StageHeaderComponent;

class OnboardingLayout implements RenderableInterface {

    use FeatHtmlEscape;

    public function __construct(
        private readonly HeadComponent $head,
        private readonly StageHeaderComponent $header,
        private readonly RenderableInterface $mainContent,
        private readonly ContactFooterComponent $footer,
        private readonly string $bodyId = "pfmOnboarding",
        private readonly string $bodyClass = "pfm-onboarding ie-body",
        private readonly ?string $bodyStyle = null
    ) {}

    public function render(): string {

        $headHtml = $this->head->render();
        $headerHtml = $this->header->render();
        $mainHtml = $this->mainContent->render();
        $footerHtml = $this->footer->render();

        $escapedBodyId = $this->escape($this->bodyId);
        $escapedBodyClass = $this->escape($this->bodyClass);
        $styleAttr = ($this->bodyStyle !== null && $this->bodyStyle !== "")
            ? ' style="' . $this->escape($this->bodyStyle) . '"'
            : "";

        return <<<HTML
<!DOCTYPE html>
<html lang="de">
{$headHtml}
<body id="{$escapedBodyId}" class="{$escapedBodyClass}"{$styleAttr}>
{$headerHtml}
<main id="pfmCommission" class="pfm-commission ie-main">
{$mainHtml}
</main>
{$footerHtml}
</body>
</html>
HTML;
    }
}
