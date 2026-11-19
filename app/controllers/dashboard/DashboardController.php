<?php
declare(strict_types=1);

namespace app\controllers\dashboard;

use app\core\Contracts\ControllerInterface;
use app\core\Contracts\RenderableInterface;
use app\layout\DashboardLayout;
use app\modules\dashboard\DashboardAuthService;
use app\modules\dashboard\DashboardStage;
use app\views\dashboard\DashboardWorkspaceView;
use app\views\dashboard\LoginView;

class DashboardController implements ControllerInterface, RenderableInterface {

    private DashboardAuthService $authService;

    public function __construct(
        private readonly DashboardStage $stage,
        ?DashboardAuthService $authService = null
    ) {
        $this->authService = $authService ?? new DashboardAuthService();
    }

    public static function handle(DashboardStage $stage): self {

        return new self($stage);
    }

    public function dispatch(): void {

        if ($this->stage === DashboardStage::Authenticate) {
            if ($this->authService->authenticate()) {
                $this->authService->redirectTo(DashboardStage::Workspace->value);
            } else {
                $this->authService->redirectTo(DashboardStage::Login->value);
            }
            return;
        }

        if ($this->stage === DashboardStage::Workspace) {
            if (!$this->authService->verifyWorkspaceAccess()) {
                $this->authService->redirectTo(DashboardStage::Login->value);
                return;
            }
        }

        if ($this->stage === DashboardStage::Login || $this->stage === DashboardStage::Logout) {
            $this->authService->prepareLoginSession();
        }

        echo $this->render();
    }

    public function render(): string {

        return match ($this->stage) {
            DashboardStage::Login, DashboardStage::Logout => $this->renderLogin(),
            DashboardStage::Workspace => $this->renderWorkspace(),
            DashboardStage::Authenticate => ""
        };
    }

    private function renderLogin(): string {

        $layout = new DashboardLayout(
            title: $this->stage->getTitle(),
            stylesheets: [
                ["href" => $this->stage->getStylesheet(), "type" => "text/css", "media" => "all"]
            ],
            bodyView: new LoginView()
        );

        return $layout->render();
    }

    private function renderWorkspace(): string {

        $layout = new DashboardLayout(
            title: $this->stage->getTitle(),
            stylesheets: [
                ["href" => $this->stage->getStylesheet(), "type" => "text/css", "media" => "all"],
                ["href" => "/public/assets/fonts/fontawesome/css/all.css"]
            ],
            bodyView: new DashboardWorkspaceView()
        );

        return $layout->render();
    }
}
