<?php
declare(strict_types=1);

namespace app\controllers\Befragung;

use app\core\Contracts\ControllerInterface;
use app\core\Contracts\RenderableInterface;
use app\core\Enums\SurveyTopic;
use app\layout\components\ContactFooterComponent;
use app\layout\components\HeadComponent;
use app\layout\components\StageHeaderComponent;
use app\layout\components\SubmissionSectionComponent;
use app\layout\ExecutionLayout;
use app\layout\OnboardingLayout;
use app\models\Befragung\SurveyDashboardModel;
use app\models\Befragung\SurveyFormListModel;
use app\models\Befragung\SurveySubmissionModel;
use app\src\Befragung\Surface;
use app\src\Befragung\SurfaceApp;
use app\views\Befragung\FinalStageView;
use app\views\Befragung\InitialStageView;

class SurveyStageController implements ControllerInterface, RenderableInterface {

    private SurfaceApp $surfaceApp;
    private SurveyTopic $topic;
    private SurveyDashboardModel $dashboardModel;
    private SurveyFormListModel $formListModel;
    private SurveySubmissionModel $submissionModel;

    /**
     * @var array{date: string, datetime: string, displayDate: string}
     */
    private array $dateInfo;

    public function __construct(
        private readonly Surface $stage,
        ?SurfaceApp $surfaceApp = null
    ) {
        $this->surfaceApp = $surfaceApp ?? $this->stage->init();

        $resolvedTopic = SurveyTopic::fromCaseName($this->surfaceApp->caseName);
        if ($resolvedTopic === null) {
            $this->redirectToRoot();
        }
        $this->topic = $resolvedTopic;

        $this->dashboardModel = new SurveyDashboardModel($this->surfaceApp->db);
        $this->formListModel = new SurveyFormListModel($this->surfaceApp->db);
        $this->submissionModel = new SurveySubmissionModel($this->surfaceApp->db);

        $dateInfo = $this->dashboardModel->getFormattedDateInfo($this->topic);
        if ($dateInfo === null) {
            $this->redirectToRoot();
        }
        $this->dateInfo = $dateInfo;
    }

    public static function handle(Surface $stage): self {

        return new self($stage);
    }

    public function dispatch(): void {

        echo $this->render();
    }

    public function render(): string {

        return match ($this->stage) {
            Surface::InitialStage => $this->renderInitialStage(),
            Surface::TransitionStage, Surface::ExecutionStage => $this->renderTransitionStage(),
            Surface::FinalStage => $this->renderFinalStage(),
            default => $this->renderInitialStage()
        };
    }

    /**
     * Verarbeitet und speichert eine Befragungs-Einreichung über die Generator-Pipeline in SurveyFormListModel.
     *
     * @param array<string, mixed> $postData
     */
    public function processSubmission(array $postData): bool {

        if ($postData === []) {
            return false;
        }

        $excelCharacters = $this->formListModel->buildExcelCharacterArray($this->topic, $postData);

        return $this->submissionModel->saveSubmission($this->topic, $postData, $excelCharacters);
    }

    public function getTopic(): SurveyTopic {

        return $this->topic;
    }

    /**
     * @return array{date: string, datetime: string, displayDate: string}
     */
    public function getDateInfo(): array {

        return $this->dateInfo;
    }

    public function getFormListModel(): SurveyFormListModel {

        return $this->formListModel;
    }

    private function renderInitialStage(): string {

        $title = $this->surfaceApp->case !== null && method_exists($this->surfaceApp->case, "getTitle")
            ? (string)$this->surfaceApp->case->getTitle()
            : $this->topic->getTitle();

        $redirectUrl = $this->surfaceApp->case !== null && method_exists($this->surfaceApp->case, "getRedirectUrl")
            ? (string)$this->surfaceApp->case->getRedirectUrl($this->dateInfo["displayDate"])
            : $this->topic->getExecutionUrl($this->dateInfo["displayDate"]);

        $head = new HeadComponent(
            stylesheets: [
                ["href" => "/public/assets/css/common.css"],
                ["href" => "/public/assets/css/pages/initial-stage.css", "media" => "all"]
            ]
        );

        $header = new StageHeaderComponent(
            title: $title,
            datetime: $this->dateInfo["datetime"],
            displayDate: $this->dateInfo["displayDate"]
        );

        $mainView = new InitialStageView(
            topic: $this->topic,
            redirectUrl: $redirectUrl
        );

        $footer = new ContactFooterComponent(
            partnerString: $this->topic->getPartnerString(),
            showIeNotice: true
        );

        $layout = new OnboardingLayout(
            head: $head,
            header: $header,
            mainContent: $mainView,
            footer: $footer,
            bodyStyle: "-ms-grid-rows: 10vh 50vh 40vh;"
        );

        return $layout->render();
    }

    private function renderTransitionStage(): string {

        $title = $this->surfaceApp->case !== null && method_exists($this->surfaceApp->case, "getTitle")
            ? (string)$this->surfaceApp->case->getTitle()
            : $this->topic->getTitle();

        $head = new HeadComponent(
            stylesheets: [
                ["href" => $this->topic->getThemeStylesheet(), "media" => "all"]
            ]
        );

        $header = new StageHeaderComponent(
            title: $title,
            datetime: $this->dateInfo["datetime"],
            displayDate: $this->dateInfo["displayDate"],
            wrapInArticle: false,
            headerClass: "pfm-execution-header ie-header",
            headerId: "pfmExecutionHeader"
        );

        $formBodyView = $this->topic->createFormView($this->dateInfo["displayDate"]);

        $submissionSection = new SubmissionSectionComponent();

        $layout = new ExecutionLayout(
            head: $head,
            header: $header,
            formBodyView: $formBodyView,
            submissionSection: $submissionSection,
            formActionUrl: $this->topic->getFinalActionUrl(),
            mainClass: $this->topic->getExecutionMainClass()
        );

        return $layout->render();
    }

    private function renderFinalStage(): string {

        if ($_POST !== []) {
            $this->processSubmission($_POST);
        }

        $title = $this->surfaceApp->case !== null && method_exists($this->surfaceApp->case, "getTitle")
            ? (string)$this->surfaceApp->case->getTitle()
            : $this->topic->getTitle();

        $head = new HeadComponent(
            stylesheets: [
                ["href" => "/public/assets/css/common.css"],
                ["href" => "/public/assets/css/pages/final-stage.css", "media" => "all"]
            ]
        );

        $header = new StageHeaderComponent(
            title: $title,
            datetime: $this->dateInfo["datetime"],
            displayDate: $this->dateInfo["displayDate"]
        );

        $mainView = new FinalStageView();

        $footer = new ContactFooterComponent(
            partnerString: null,
            showIeNotice: false
        );

        $layout = new OnboardingLayout(
            head: $head,
            header: $header,
            mainContent: $mainView,
            footer: $footer
        );

        return $layout->render();
    }

    private function redirectToRoot(): never {

        $host = $_SERVER["HTTP_HOST"] ?? "localhost";
        $scheme = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") ? "https" : "http";

        if (!headers_sent()) {
            header("Location: {$scheme}://{$host}/");
        }
        exit();
    }
}
