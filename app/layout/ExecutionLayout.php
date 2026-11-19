<?php
declare(strict_types=1);

namespace app\layout;

use app\core\Contracts\RenderableInterface;
use app\core\Traits\FeatHtmlEscape;
use app\layout\components\HeadComponent;
use app\layout\components\StageHeaderComponent;
use app\layout\components\SubmissionSectionComponent;

class ExecutionLayout implements RenderableInterface {

    use FeatHtmlEscape;

    public function __construct(
        private readonly HeadComponent $head,
        private readonly StageHeaderComponent $header,
        private readonly RenderableInterface $formBodyView,
        private readonly SubmissionSectionComponent $submissionSection,
        private readonly string $formActionUrl,
        private readonly string $mainClass = "ie-main",
        private readonly string $formId = "startForm",
        private readonly string $formClass = "pfm-factory"
    ) {}

    public function render(): string {

        $headHtml = $this->head->render();
        $headerHtml = $this->header->render();
        $formBodyHtml = $this->formBodyView->render();
        $submissionHtml = $this->submissionSection->render();

        $escapedAction = $this->escape($this->formActionUrl);
        $escapedMainClass = $this->escape($this->mainClass);
        $escapedFormId = $this->escape($this->formId);
        $escapedFormClass = $this->escape($this->formClass);

        return <<<HTML
<!DOCTYPE html>
<html lang="de">
{$headHtml}
<body class="pfm ie-body">
{$headerHtml}
<main id="pfmExecutionStage" class="{$escapedMainClass}">
    <form action="{$escapedAction}" method="post" id="{$escapedFormId}" class="{$escapedFormClass}">
{$formBodyHtml}
{$submissionHtml}
    </form>
</main>
<footer id="pfmExecutionFooter" class="pfm-execution-footer ie-footer">

</footer>
</body>
</html>
HTML;
    }
}
