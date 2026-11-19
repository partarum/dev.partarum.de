<?php
declare(strict_types=1);

namespace app\layout\components;

use app\core\Contracts\RenderableInterface;
use app\core\Traits\FeatHtmlEscape;

class HeadComponent implements RenderableInterface {

    use FeatHtmlEscape;

    /**
     * @param string $title
     * @param array<int, array{href: string, media?: string|null}> $stylesheets
     * @param string $favicon
     */
    public function __construct(
        private readonly string $title = "Dr. Franz-Josef Lückge Consulting",
        private readonly array $stylesheets = [],
        private readonly string $favicon = "/public/assets/media/favicon.ico"
    ) {}

    public function render(): string {

        $escapedTitle = $this->escape($this->title);
        $escapedFavicon = $this->escape($this->favicon);

        $linksHtml = "";
        foreach ($this->stylesheets as $sheet) {
            $href = $this->escape($sheet["href"] ?? "");
            if ($href === "") {
                continue;
            }
            $mediaAttr = isset($sheet["media"]) && $sheet["media"] !== ""
                ? ' media="' . $this->escape($sheet["media"]) . '"'
                : "";
            $linksHtml .= "    <link rel=\"stylesheet\" type=\"text/css\" href=\"{$href}\"{$mediaAttr}>\n";
        }

        return <<<HTML
<head>
    <meta charset="UTF-8">
    <title>{$escapedTitle}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    <link rel="shortcut icon" href="{$escapedFavicon}">

{$linksHtml}    <link rel="stylesheet" href="/public/assets/fonts/fontawesome/css/all.min.css">
</head>
HTML;
    }
}
