<?php
declare(strict_types=1);

namespace app\views\dashboard;

use app\core\Contracts\RenderableInterface;
use app\core\Traits\FeatHtmlEscape;

class DashboardWorkspaceView implements RenderableInterface {

    use FeatHtmlEscape;

    public function __construct(
        private readonly string $moduleScript = "/public/assets/js/dashboardPage.js"
    ) {}

    public function render(): string {

        $escapedScript = $this->escape($this->moduleScript);

        return <<<HTML
    <body class="prt-area">
        <header id="header" class="prt-a1 prt-cont"></header>
        <main id="content" class="prt-a2 prt-cont"></main>
        <footer id="footer" class="prt-a3 prt-cont"></footer>
        <script type="module" crossorigin="use-credentials" src="{$escapedScript}"></script>
    </body>
HTML;
    }
}
