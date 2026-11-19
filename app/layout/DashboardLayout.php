<?php
declare(strict_types=1);

namespace app\layout;

use app\core\Contracts\RenderableInterface;
use app\core\Traits\FeatHtmlEscape;

class DashboardLayout implements RenderableInterface {

    use FeatHtmlEscape;

    /**
     * @param string $title
     * @param array<int, array{href: string, media?: string|null}> $stylesheets
     * @param RenderableInterface $bodyView
     */
    public function __construct(
        private readonly string $title,
        private readonly array $stylesheets,
        private readonly RenderableInterface $bodyView
    ) {}

    public function render(): string {

        $escapedTitle = $this->escape($this->title);
        $linksHtml = "";

        foreach ($this->stylesheets as $sheet) {
            $href = $this->escape($sheet["href"] ?? "");
            if ($href === "") {
                continue;
            }
            $mediaAttr = isset($sheet["media"]) && $sheet["media"] !== ""
                ? ' media="' . $this->escape($sheet["media"]) . '"'
                : "";
            $typeAttr = isset($sheet["type"]) && $sheet["type"] !== ""
                ? ' type="' . $this->escape($sheet["type"]) . '"'
                : "";
            $linksHtml .= "    <link rel=\"stylesheet\"{$typeAttr} href=\"{$href}\"{$mediaAttr}>\n";
        }

        $bodyHtml = $this->bodyView->render();

        return <<<HTML
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>{$escapedTitle}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

{$linksHtml}</head>
{$bodyHtml}
</html>
HTML;
    }
}
