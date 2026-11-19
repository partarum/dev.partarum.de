<?php
declare(strict_types=1);

namespace app\controllers\dashboard;

use app\core\Contracts\ControllerInterface;
use app\core\Enums\SurveyTopic;
use app\models\Befragung\SurveyDashboardModel;
use app\models\Befragung\SurveySubmissionModel;
use app\models\Befragung\SurveyTokenModel;
use app\modules\dashboard\DashboardApiAction;
use app\modules\dashboard\DashboardExcelService;
use Medoo\Medoo;
use Partarum\Partarum;
use Partarum\PartarumPHP\HTTP\API;

class DashboardApiController implements ControllerInterface {

    private Partarum $backend;
    private API $api;
    private ?Medoo $dbOverride;
    private DashboardExcelService $excelService;
    private ?bool $authorizedOverride;

    public function __construct(
        private readonly DashboardApiAction $action,
        ?Partarum $backend = null,
        ?API $api = null,
        ?Medoo $db = null,
        ?DashboardExcelService $excelService = null,
        ?bool $authorizedOverride = null
    ) {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }

        if (!class_exists(Partarum::class)) {
            require_once dirname(__DIR__, 3) . "/Partarum/Partarum.php";
        }

        $this->backend = $backend ?? new Partarum($this->action->getPartarumAppName());
        $this->api = $api ?? new API();
        $this->dbOverride = $db;
        $this->excelService = $excelService ?? new DashboardExcelService();
        $this->authorizedOverride = $authorizedOverride;
    }

    public static function handle(DashboardApiAction $action): self {

        return new self($action);
    }

    public function dispatch(): void {

        $bearerToken = $this->api->fromBearerToken();

        if (!$this->isAuthorized($bearerToken)) {
            $this->api
                ->createJSONResponse($this->action->getForbiddenPayload($bearerToken))
                ->run(403);
            return;
        }

        match ($this->action) {
            DashboardApiAction::CheckSurvey => $this->dispatchCheckSurvey(),
            DashboardApiAction::StartSurvey => $this->dispatchStartSurvey(),
            DashboardApiAction::StopSurvey => $this->dispatchStopSurvey(),
            DashboardApiAction::GetExcelData => $this->dispatchGetExcelData(),
            DashboardApiAction::GetExcelFile => $this->dispatchGetExcelFile(),
            DashboardApiAction::GetToken => $this->dispatchGetToken(),
            DashboardApiAction::GetTokenExcel => $this->dispatchGetTokenExcel()
        };
    }

    private function isAuthorized(?string $bearerToken): bool {

        if ($this->authorizedOverride !== null) {
            return $this->authorizedOverride;
        }

        return $bearerToken !== null && $this->backend->isIntoCache("Bearer:" . $bearerToken);
    }

    private function getDefaultDb(): Medoo {

        return $this->dbOverride ?? $this->backend->createDBConnection();
    }

    private function getTokenExcelDb(): Medoo {

        if ($this->dbOverride !== null) {
            return $this->dbOverride;
        }

        $env = $this->backend->loadSecret("DB/db2");
        if (is_array($env) && ($env["DB_NAME"] ?? "") !== "") {
            return $this->backend->createDBConnection([
                "type" => $env["DB_TYPE"] ?? "mysql",
                "host" => $env["DB_HOST"] ?? "localhost",
                "database" => $env["DB_NAME"] ?? "",
                "username" => $env["DB_USER"] ?? "",
                "password" => $env["DB_PW"] ?? ""
            ]);
        }

        return $this->backend->createDBConnection();
    }

    /**
     * Liest die 5 Topic-Parameter (`kvh` .. `rhd`) aus dem POST-Request.
     *
     * @return array<string, mixed>
     */
    private function collectTopicPostValues(bool $lowercaseKeys = false): array {

        $values = [];

        foreach (SurveyTopic::cases() as $topic) {
            $lower = strtolower($topic->value);
            $key = $lowercaseKeys ? $lower : $topic->value;
            $values[$key] = $this->api->fromPOST($lower);
        }

        return $values;
    }

    private function dispatchCheckSurvey(): void {

        $db = $this->getDefaultDb();
        $dashboardModel = new SurveyDashboardModel($db);
        $submissionModel = new SurveySubmissionModel($db);

        $payload = $dashboardModel->evaluateSurveyStatuses($submissionModel);

        $this->api->createJSONResponse($payload)->run();
    }

    private function dispatchStartSurvey(): void {

        $db = $this->getDefaultDb();
        $dashboardModel = new SurveyDashboardModel($db);

        $payload = $dashboardModel->startSurveys(
            allDate: $this->api->fromPOST("all"),
            topicDates: $this->collectTopicPostValues()
        );

        $this->api->createJSONResponse($payload)->run();
    }

    private function dispatchStopSurvey(): void {

        $db = $this->getDefaultDb();
        $dashboardModel = new SurveyDashboardModel($db);
        $submissionModel = new SurveySubmissionModel($db);

        $payload = $dashboardModel->stopSurveys(
            allFlag: $this->api->fromPOST("all"),
            selectedTopics: $this->collectTopicPostValues(),
            submissionModel: $submissionModel
        );

        $this->api->createJSONResponse($payload)->run();
    }

    private function dispatchGetExcelData(): void {

        $db = $this->getDefaultDb();
        $submissionModel = new SurveySubmissionModel($db);

        $this->api->createJSONResponse([
            "survey_count" => $submissionModel->getCountsSummary()
        ])->run();
    }

    private function dispatchGetExcelFile(): void {

        $db = $this->getDefaultDb();
        $dashboardModel = new SurveyDashboardModel($db);
        $submissionModel = new SurveySubmissionModel($db);

        $requestType = $this->api->fromPOST("type");

        $spreadsheet = $this->excelService->buildSurveySpreadsheet(
            dashboardModel: $dashboardModel,
            submissionModel: $submissionModel,
            allFlag: $this->api->fromPOST("all"),
            selectedTopics: $this->collectTopicPostValues()
        );

        if ($requestType === "test") {
            echo "hallo";
            return;
        }

        $res = $this->api->createResponse();
        $res->setHeader("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        $res->setHeader('Content-Disposition: attachment;filename="Erhebung.xlsx"');
        $res->setHeader("Cache-Control: max-age=0");
        $res->run();

        $this->excelService->streamSpreadsheet($spreadsheet);
    }

    private function dispatchGetToken(): void {

        $db = $this->getDefaultDb();
        $tokenModel = new SurveyTokenModel($db);

        $payload = $tokenModel->upsertTokens(
            allFlag: $this->api->fromPOST("all"),
            selectedTopics: $this->collectTopicPostValues(lowercaseKeys: true),
            secretKey: (string)($_ENV["JWT_SECRET_KEY"] ?? "")
        );

        $this->api->createJSONResponse($payload)->run();
    }

    private function dispatchGetTokenExcel(): void {

        $db = $this->getTokenExcelDb();
        $tokenModel = new SurveyTokenModel($db);

        $spreadsheet = $this->excelService->buildTokenSpreadsheet($tokenModel);

        $res = $this->api->createResponse();
        $res->setHeader("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        $res->setHeader('Content-Disposition: attachment;filename="example.xlsx"');
        $res->setHeader("Cache-Control: max-age=0");
        $res->run();

        $this->excelService->streamSpreadsheet($spreadsheet);
    }
}
