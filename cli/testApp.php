<?php
declare(strict_types=1);

require_once dirname(__DIR__) . "/vendor/autoload.php";

use app\controllers\Befragung\SurveyStageController;
use app\controllers\dashboard\DashboardApiController;
use app\controllers\dashboard\DashboardController;
use app\core\Contracts\ControllerInterface;
use app\core\Contracts\ModelInterface;
use app\core\Contracts\RenderableInterface;
use app\core\Enums\SurveyTopic;
use app\core\Traits\FeatHtmlEscape;
use app\core\Traits\FeatModelDatabase;
use app\layout\components\ContactFooterComponent;
use app\layout\components\HeadComponent;
use app\layout\components\StageHeaderComponent;
use app\layout\components\SubmissionSectionComponent;
use app\layout\DashboardLayout;
use app\layout\ExecutionLayout;
use app\layout\OnboardingLayout;
use app\models\Befragung\SurveyDashboardModel;
use app\models\Befragung\SurveyFormListModel;
use app\models\Befragung\SurveySubmissionModel;
use app\models\Befragung\SurveyTokenModel;
use app\modules\dashboard\DashboardApiAction;
use app\modules\dashboard\DashboardAuthService;
use app\modules\dashboard\DashboardExcelService;
use app\modules\dashboard\DashboardStage;
use app\src\Befragung\Surface;
use app\src\Befragung\SurfaceApp;
use app\src\Befragung\SurfaceExecutionStage;
use app\src\Befragung\SurfaceFinalStage;
use app\src\Befragung\SurfaceInitialStage;
use app\src\Befragung\SurfaceSetupStage;
use app\src\Befragung\SurfaceTransitionStage;
use app\views\Befragung\FinalStageView;
use app\views\Befragung\forms\KVHFormView;
use app\views\Befragung\forms\LSHFormView;
use app\views\Befragung\forms\NSHFormView;
use app\views\Befragung\forms\RHBFormView;
use app\views\Befragung\forms\RHDFormView;
use app\views\Befragung\InitialStageView;
use app\views\dashboard\DashboardWorkspaceView;
use app\views\dashboard\LoginView;
use Medoo\Medoo;
use Partarum\Partarum;
use Partarum\PartarumPHP\HTTP\API;
use Partarum\PartarumSurface\Stages\ExecutionStageInterface;
use Partarum\PartarumSurface\Stages\FinalStageInterface;
use Partarum\PartarumSurface\Stages\InitialStageInterface;
use Partarum\PartarumSurface\Stages\SetupStageInterface;
use Partarum\PartarumSurface\Stages\TransitionStageInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$rootDir = rtrim(dirname(__DIR__), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

(!defined("ROOT")) && define("ROOT", $rootDir);
(!defined("SLASH")) && define("SLASH", DIRECTORY_SEPARATOR);
(!defined("PARTARUM_PATH")) && define("PARTARUM_PATH", $rootDir . "Partarum");

$passed = 0;
$failed = 0;

$check = static function (bool $condition, string $label) use (&$passed, &$failed): void {
    if ($condition) {
        $passed++;
        echo "[OK]   " . $label . PHP_EOL;
    } else {
        $failed++;
        fwrite(STDERR, "[FAIL] " . $label . PHP_EOL);
    }
};

/**
 * PDOStatement-Stub für rowCount()-Prüfungen bei delete/update.
 */
final class AppMockPDOStatement extends PDOStatement {

    public function __construct(private readonly int $affectedRows) {
    }

    public function rowCount(): int {

        return $this->affectedRows;
    }
}

/**
 * Leichtgewichtiges In-Memory Medoo-Double für unabhängige Offline-Tests der app-Models & Controller.
 */
final class AppInMemoryMedoo extends Medoo {

    /**
     * @var array<string, array<string, mixed>>
     */
    public array $dashboardRows = [];

    /**
     * @var array<string, array<string, mixed>>
     */
    public array $formListRows = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $insertedSurveys = [];

    /**
     * @var array<string, array{name: string, value: string}>
     */
    public array $tokenRows = [];

    public function __construct() {
        foreach (["KVH", "LSH", "NSH", "RHB", "RHD"] as $topic) {
            $this->dashboardRows[$topic] = [
                "topic" => $topic,
                "survey_start" => "2026-11-18",
                "survey_stop" => "2026-11-23",
                "survey_has_been_set" => "2026-11-17",
                "survey_has_been_stopped" => null,
                "survey_status" => 1,
                "survey_diff" => 0,
                "survey_max_diff" => 5
            ];
        }

        $this->formListRows["KVH"] = [
            "survey_fields" => json_encode(["b_1_n_1", "b_2_n_1", "b_5_n_1", "a_1_n_1", "e_1_n_1"]),
            "survey_areas" => json_encode(["b_1", "b_2", "b_5", "a_1", "e_1"]),
            "survey_sections" => null,
            "survey_content" => json_encode([
                "b_1" => [
                    "fields" => [
                        "b_1_n_1" => [
                            ["label" => "gut", "id" => "b_1_1", "value" => "100"],
                            ["label" => "befriedigend", "id" => "b_1_2", "value" => "010"],
                            ["label" => "schlecht", "id" => "b_1_3", "value" => "001"]
                        ]
                    ]
                ],
                "b_2" => [
                    "fields" => [
                        "b_2_n_1" => [
                            ["label" => "gestiegen", "id" => "b_2_1", "value" => "100"],
                            ["label" => "unverändert", "id" => "b_2_2", "value" => "010"],
                            ["label" => "gefallen", "id" => "b_2_3", "value" => "001"]
                        ]
                    ]
                ],
                "b_5" => [
                    "fields" => [
                        "b_5_n_1" => [
                            ["label" => "6er", "id" => "b_5_1", "value" => ""]
                        ]
                    ]
                ],
                "a_1" => [
                    "fields" => [
                        "a_1_n_1" => [
                            ["label" => "Anmerkungen", "id" => "a_1_1", "value" => ""]
                        ]
                    ]
                ],
                "e_1" => [
                    "fields" => [
                        "e_1_n_1" => [
                            ["label" => "Email", "id" => "e_1_1", "value" => ""]
                        ]
                    ]
                ]
            ])
        ];
    }

    public function get(string $table, $join = null, $columns = null, $where = null) {
        $actualColumns = $columns === null ? $join : $columns;
        $actualWhere = $columns === null ? $columns : $where;
        if ($where === null && is_array($columns)) {
            $actualColumns = $join;
            $actualWhere = $columns;
        }

        $topic = is_array($actualWhere) ? (string)($actualWhere["topic"] ?? "") : "";

        if ($table === "survey_dashboard") {
            $row = $this->dashboardRows[$topic] ?? null;
            if ($row === null) {
                return null;
            }
            if (is_string($actualColumns)) {
                return $row[$actualColumns] ?? null;
            }
            if (is_array($actualColumns)) {
                return array_intersect_key($row, array_flip($actualColumns));
            }
            return $row;
        }

        if ($table === "survey_form_list") {
            $row = $this->formListRows[$topic] ?? null;
            if ($row === null) {
                return null;
            }
            if (is_string($actualColumns)) {
                return $row[$actualColumns] ?? null;
            }
            if (is_array($actualColumns)) {
                return array_intersect_key($row, array_flip($actualColumns));
            }
            return $row;
        }

        return null;
    }

    public function select(string $table, $join, $columns = null, $where = null): ?array {
        $actualColumns = $join;
        $actualWhere = is_array($columns) ? $columns : $where;

        if ($table === "survey_dashboard") {
            $topic = is_array($actualWhere) ? (string)($actualWhere["topic"] ?? "") : "";
            if ($topic !== "" && isset($this->dashboardRows[$topic])) {
                $row = $this->dashboardRows[$topic];
                if (is_array($actualColumns)) {
                    return [array_intersect_key($row, array_flip($actualColumns))];
                }
                return [$row];
            }
            return [];
        }

        if ($table === "survey") {
            $topic = is_array($actualWhere) ? (string)($actualWhere["topic"] ?? "") : "";
            $matching = array_values(array_filter(
                $this->insertedSurveys,
                static fn(array $row): bool => $topic === "" || ($row["topic"] ?? "") === $topic
            ));

            if (is_string($actualColumns)) {
                return array_map(static fn(array $row) => $row[$actualColumns] ?? null, $matching);
            }
            return $matching;
        }

        if ($table === "token") {
            return array_values($this->tokenRows);
        }

        return [];
    }

    public function insert(string $table, array $values, ?string $primaryKey = null): ?PDOStatement {
        if ($table === "survey") {
            $this->insertedSurveys[] = $values;
        }

        if ($table === "token") {
            $rows = isset($values[0]) && is_array($values[0]) ? $values : [$values];
            foreach ($rows as $row) {
                $name = (string)($row["name"] ?? "");
                if ($name !== "") {
                    $this->tokenRows[$name] = [
                        "name" => $name,
                        "value" => (string)($row["value"] ?? "")
                    ];
                }
            }
        }

        return new AppMockPDOStatement(1);
    }

    public function update(string $table, $data, $where = null): ?PDOStatement {
        if ($table === "survey_dashboard" && is_array($data) && is_array($where)) {
            $topic = (string)($where["topic"] ?? "");
            if ($topic !== "" && isset($this->dashboardRows[$topic])) {
                $this->dashboardRows[$topic] = array_merge($this->dashboardRows[$topic], $data);
                return new AppMockPDOStatement(1);
            }
        }

        if ($table === "token" && is_array($data) && is_array($where)) {
            $name = (string)($where["name"] ?? "");
            if ($name !== "" && isset($this->tokenRows[$name])) {
                $this->tokenRows[$name]["value"] = (string)($data["value"] ?? "");
                return new AppMockPDOStatement(1);
            }
        }

        return new AppMockPDOStatement(0);
    }

    public function delete(string $table, $where): ?PDOStatement {
        if ($table === "survey" && is_array($where)) {
            $topic = (string)($where["topic"] ?? "");
            $before = count($this->insertedSurveys);
            $this->insertedSurveys = array_values(array_filter(
                $this->insertedSurveys,
                static fn(array $row): bool => ($row["topic"] ?? "") !== $topic
            ));
            $deleted = $before - count($this->insertedSurveys);
            return new AppMockPDOStatement($deleted);
        }

        return new AppMockPDOStatement(0);
    }

    public function has(string $table, $join, $where = null): bool {
        $actualWhere = is_array($join) ? $join : $where;
        if ($table === "token" && is_array($actualWhere)) {
            $name = (string)($actualWhere["name"] ?? "");
            return isset($this->tokenRows[$name]);
        }
        return false;
    }

    public function count(string $table, $join = null, $column = null, $where = null): ?int {
        if ($table === "survey") {
            $topic = is_array($join) ? (string)($join["topic"] ?? "") : "";
            return count(array_filter(
                $this->insertedSurveys,
                static fn(array $row): bool => ($row["topic"] ?? "") === $topic
            ));
        }
        return 0;
    }
}

echo "=== App Architecture & Layer Smoke Test ===" . PHP_EOL;

// 1. Autoloading von Contracts, Traits, Enums, Models, Views, Layouts & Controllern
$appInterfaces = [
    ControllerInterface::class,
    ModelInterface::class,
    RenderableInterface::class
];
foreach ($appInterfaces as $iface) {
    $check(interface_exists($iface), "Autoload App-Contract: {$iface}");
}

$appTraits = [
    FeatHtmlEscape::class,
    FeatModelDatabase::class
];
foreach ($appTraits as $trait) {
    $check(trait_exists($trait), "Autoload App-Trait: {$trait}");
}

$appEnums = [
    SurveyTopic::class,
    DashboardStage::class,
    DashboardApiAction::class,
    Surface::class,
    SurfaceInitialStage::class,
    SurfaceSetupStage::class,
    SurfaceTransitionStage::class,
    SurfaceExecutionStage::class,
    SurfaceFinalStage::class
];
foreach ($appEnums as $enum) {
    $check(enum_exists($enum), "Autoload App-Enum: {$enum}");
}

$appClasses = [
    SurfaceApp::class,
    SurveyDashboardModel::class,
    SurveyFormListModel::class,
    SurveySubmissionModel::class,
    SurveyTokenModel::class,
    DashboardAuthService::class,
    DashboardExcelService::class,
    HeadComponent::class,
    StageHeaderComponent::class,
    ContactFooterComponent::class,
    SubmissionSectionComponent::class,
    OnboardingLayout::class,
    ExecutionLayout::class,
    DashboardLayout::class,
    InitialStageView::class,
    FinalStageView::class,
    KVHFormView::class,
    LSHFormView::class,
    NSHFormView::class,
    RHBFormView::class,
    RHDFormView::class,
    LoginView::class,
    DashboardWorkspaceView::class,
    SurveyStageController::class,
    DashboardController::class,
    DashboardApiController::class
];
foreach ($appClasses as $cls) {
    $check(class_exists($cls), "Autoload App-Class: {$cls}");
}

// 2. Stage-Enums implementieren PartarumSurface-Stage-Interfaces & Surface::getCase / getDate
$check(is_subclass_of(SurfaceInitialStage::class, InitialStageInterface::class), "SurfaceInitialStage implementiert InitialStageInterface");
$check(is_subclass_of(SurfaceSetupStage::class, SetupStageInterface::class), "SurfaceSetupStage implementiert SetupStageInterface");
$check(is_subclass_of(SurfaceTransitionStage::class, TransitionStageInterface::class), "SurfaceTransitionStage implementiert TransitionStageInterface");
$check(is_subclass_of(SurfaceExecutionStage::class, ExecutionStageInterface::class), "SurfaceExecutionStage implementiert ExecutionStageInterface");
$check(is_subclass_of(SurfaceFinalStage::class, FinalStageInterface::class), "SurfaceFinalStage implementiert FinalStageInterface");
$check(Surface::getCase(Surface::InitialStage, "/KVH") === SurfaceInitialStage::KVH, "Surface::getCase() löst InitialStage '/KVH' auf SurfaceInitialStage::KVH auf");
$check(Surface::getCase(Surface::SetupStage, "/unbekannt") === null, "Surface::getCase() liefert null bei unbekannter Route");
$surfaceDateInfo = Surface::getDate("2026-11-16");
$check(is_array($surfaceDateInfo) && $surfaceDateInfo["date"] === "2026-11-16", "Surface::getDate() formatiert gültiges Datum");
$check(Surface::getDate("ungueltig") === null, "Surface::getDate() liefert null bei ungültigem Datum");

// 3. SurveyTopic Enum-Methoden
$check(SurveyTopic::fromCaseName("/kvh") === SurveyTopic::KVH, "SurveyTopic::fromCaseName() normalisiert '/kvh' zu SurveyTopic::KVH");
$check(SurveyTopic::fromCaseName("unbekannt") === null, "SurveyTopic::fromCaseName() liefert null bei unbekanntem Topic");
$check(SurveyTopic::KVH->getTitle() === "Konstruktionsvollholz", "SurveyTopic::getTitle() liefert Fachtitel");
$check(str_contains(SurveyTopic::RHB->getPartnerString(), "Forstkammer Baden-Württemberg"), "SurveyTopic::getPartnerString() liefert Partner-Headline");
$check(SurveyTopic::LSH->getThemeStylesheet() === "/public/assets/css/themes/LSH/theme.css", "SurveyTopic::getThemeStylesheet() liefert Theme-CSS-Pfad");
$check(SurveyTopic::NSH->getExecutionUrl("November 2026") === "NSH-Befragung?date=November_2026", "SurveyTopic::getExecutionUrl() baut Ziel-URL mit Unterstrich-Datum");
$check(SurveyTopic::RHD->getFinalActionUrl() === "./RHD-Final", "SurveyTopic::getFinalActionUrl() liefert Form-Action-URL");
$check(SurveyTopic::KVH->getExecutionMainClass() === "ie-main pre-pfm-execution-stage", "SurveyTopic::getExecutionMainClass() liefert spezifische IE-Main-Klasse für KVH");
$check(SurveyTopic::LSH->getExecutionMainClass() === "ie-main", "SurveyTopic::getExecutionMainClass() liefert Standard-IE-Main-Klasse für LSH..RHD");

foreach (SurveyTopic::cases() as $topicCase) {
    $formView = $topicCase->createFormView("November 2026");
    $html = $formView->render();
    $check(
        $formView instanceof RenderableInterface
        && str_contains($html, 'class="pfm-area pre-form-area"')
        && str_contains($html, 'name="b_1_n_1"'),
        "SurveyTopic::{$topicCase->name}->createFormView() rendert IE-kompatibles Formular-HTML (" . strlen($html) . " Bytes)"
    );
}

// 4. Models & Generator-Pipelines (SurveyDashboardModel, SurveyFormListModel, SurveySubmissionModel, SurveyTokenModel)
$mockDb = new AppInMemoryMedoo();

$dashboardModel = new SurveyDashboardModel($mockDb);
$check($dashboardModel->getDatabase() === $mockDb, "SurveyDashboardModel::getDatabase() liefert Medoo-Instanz");
$check($dashboardModel->getStartDate(SurveyTopic::KVH) === "2026-11-18", "SurveyDashboardModel::getStartDate() liest survey_start");
$dateInfo = $dashboardModel->getFormattedDateInfo(SurveyTopic::KVH);
$check(
    is_array($dateInfo)
    && $dateInfo["date"] === "2026-11-18"
    && $dateInfo["datetime"] === "2026-11"
    && str_contains(strtolower($dateInfo["displayDate"]), "november 2026"),
    "SurveyDashboardModel::getFormattedDateInfo() formatiert Datum über Surface::getDate()"
);
$yieldedTopics = iterator_to_array($dashboardModel->yieldAllTopics());
$check(count($yieldedTopics) === 5 && isset($yieldedTopics["KVH"], $yieldedTopics["RHD"]), "SurveyDashboardModel::yieldAllTopics() iteriert als Generator über alle 5 Topics");

$formListModel = new SurveyFormListModel($mockDb);
$kvhConfig = $formListModel->getConfiguration(SurveyTopic::KVH);
$check(
    $kvhConfig["fields"] === ["b_1_n_1", "b_2_n_1", "b_5_n_1", "a_1_n_1", "e_1_n_1"]
    && $kvhConfig["sections"] === [],
    "SurveyFormListModel::getConfiguration() dekodiert JSON-Spalten und löst NULL-Spalten (survey_sections) warnungsfrei zu [] auf"
);

$samplePost = [
    "b_1_n_1" => "100",
    "b_5_n_1" => "",
    "a_1_n_1" => "Alles bestens",
    "e_1_n_1" => "test@example.org"
];
$mergedFields = iterator_to_array($formListModel->yieldMergedPostFields(SurveyTopic::KVH, $samplePost));
$check(
    $mergedFields["b_1_n_1"] === "100"
    && $mergedFields["b_2_n_1"] === 0
    && $mergedFields["b_5_n_1"] === "",
    "SurveyFormListModel::yieldMergedPostFields() ergänzt fehlende Formularfelder per Generator mit Default 0"
);

$excelChars = $formListModel->buildExcelCharacterArray(SurveyTopic::KVH, $samplePost);
$check(
    $excelChars === ["A", 0, "X", "Alles bestens", "test@example.org"],
    "SurveyFormListModel::yieldExcelCharacters() mappt Radio-Werte auf Buchstaben ('A'), fehlende auf 0, leeren Text auf 'X' und Freitext auf Wert"
);

$submissionModel = new SurveySubmissionModel($mockDb);
$check($submissionModel->saveSubmission(SurveyTopic::KVH, [], []) === false, "SurveySubmissionModel::saveSubmission() ignoriert leere POST-Daten (F5-Reload-Schutz)");
$check(
    $submissionModel->saveSubmission(SurveyTopic::KVH, $samplePost, $excelChars) === true
    && $submissionModel->countByTopic(SurveyTopic::KVH) === 1,
    "SurveySubmissionModel::saveSubmission() speichert Einreichung in survey und erhöht Zähler"
);

// 5. Layout, Components & Views (Onboarding, Execution, Dashboard)
$headComp = new HeadComponent("Test Titel", [["href" => "/public/assets/css/common.css"]]);
$headerComp = new StageHeaderComponent("Konstruktionsvollholz", "2026-11", "November 2026");
$footerComp = new ContactFooterComponent(SurveyTopic::KVH->getPartnerString(), true);
$initialView = new InitialStageView(SurveyTopic::KVH, "KVH-Befragung?date=November_2026");
$onboardingHtml = (new OnboardingLayout($headComp, $headerComp, $initialView, $footerComp, bodyStyle: "-ms-grid-rows: 10vh 50vh 40vh;"))->render();

$check(
    str_contains($onboardingHtml, "<h1>Konstruktionsvollholz</h1>")
    && str_contains($onboardingHtml, '<time datetime="2026-11">November 2026</time>')
    && str_contains($onboardingHtml, 'href="KVH-Befragung?date=November_2026"')
    && str_contains($onboardingHtml, 'id="ieInfoText"'),
    "OnboardingLayout setzt HeadComponent, StageHeaderComponent, InitialStageView und ContactFooterComponent korrekt zusammen"
);

$execHeader = new StageHeaderComponent("Laubschnittholz", "2026-11", "November 2026", false, "pfm-execution-header ie-header", "pfmExecutionHeader");
$execLayout = new ExecutionLayout(
    $headComp,
    $execHeader,
    new LSHFormView("November 2026"),
    new SubmissionSectionComponent(),
    "./LSH-Final"
);
$execHtml = $execLayout->render();
$check(
    str_contains($execHtml, 'id="pfmExecutionHeader"')
    && str_contains($execHtml, 'action="./LSH-Final"')
    && str_contains($execHtml, 'id="pfmFinalStage"'),
    "ExecutionLayout rendert vollständiges Befragungsformular inklusive SubmissionSectionComponent"
);

// 6. SurveyStageController mit injiziertem SurfaceApp-Double
$reflectionSurfaceApp = new ReflectionClass(SurfaceApp::class);
/** @var SurfaceApp $fakeSurfaceApp */
$fakeSurfaceApp = $reflectionSurfaceApp->newInstanceWithoutConstructor();
$fakeSurfaceApp->db = $mockDb;
$fakeSurfaceApp->case = SurfaceInitialStage::KVH;
$fakeSurfaceApp->caseName = "KVH";

$initialCtrl = new SurveyStageController(Surface::InitialStage, $fakeSurfaceApp);
$initialCtrlHtml = $initialCtrl->render();
$check(
    $initialCtrl->getTopic() === SurveyTopic::KVH
    && str_contains($initialCtrlHtml, 'href="KVH-Befragung?date=November_2026"')
    && !str_contains($initialCtrlHtml, "Juni 2025"),
    "SurveyStageController rendert InitialStage mit echtem DB-Datum (ohne 'Juni 2025'-Override)"
);

$fakeSurfaceApp->case = SurfaceTransitionStage::KVH;
$transitionCtrl = new SurveyStageController(Surface::TransitionStage, $fakeSurfaceApp);
$transitionCtrlHtml = $transitionCtrl->render();
$check(
    str_contains($transitionCtrlHtml, "Produktionsmenge Fi/Ta NSi-KVH November 2026")
    && str_contains($transitionCtrlHtml, 'action="./KVH-Final"'),
    "SurveyStageController rendert TransitionStage für KVH mit dynamischem Berichtsmonat"
);

$_POST = $samplePost;
$fakeSurfaceApp->case = SurfaceFinalStage::KVH;
$finalCtrl = new SurveyStageController(Surface::FinalStage, $fakeSurfaceApp);
$finalCtrlHtml = $finalCtrl->render();
$_POST = [];
$check(
    str_contains($finalCtrlHtml, "Ihre Eingaben wurde erfolgreich gespeichert.")
    && $submissionModel->countByTopic(SurveyTopic::KVH) === 2,
    "SurveyStageController verarbeitet POST-Einreichung in FinalStage und rendert Bestätigungsseite"
);

// 7. DashboardController, DashboardStage & Views
$loginCtrl = DashboardController::handle(DashboardStage::Login);
$loginHtml = $loginCtrl->render();
$check(
    str_contains($loginHtml, 'action="dashboard"')
    && str_contains($loginHtml, 'href="/public/assets/css_backup/login.css"'),
    "DashboardController rendert LoginStage über DashboardLayout und LoginView"
);

$workspaceCtrl = DashboardController::handle(DashboardStage::Workspace);
$workspaceHtml = $workspaceCtrl->render();
$check(
    str_contains($workspaceHtml, 'src="/public/assets/js/dashboardPage.js"')
    && str_contains($workspaceHtml, 'class="prt-area"'),
    "DashboardController rendert WorkspaceStage über DashboardLayout und DashboardWorkspaceView"
);

// 8. Dashboard API-Layer (SurveyDashboardModel-Lifecycle, SurveyTokenModel, DashboardExcelService & DashboardApiController)
$countsSummary = $submissionModel->getCountsSummary();
$check(
    $countsSummary["KVH"] === 2 && $countsSummary["ALL"] === 2 && $countsSummary["LSH"] === 0,
    "SurveySubmissionModel::getCountsSummary() summiert Befragungen über yieldCountsByTopic() inkl. 'ALL'"
);

$yieldedExcelRows = iterator_to_array($submissionModel->yieldExcelRowsByTopic(SurveyTopic::KVH));
$check(
    count($yieldedExcelRows) === 2
    && $yieldedExcelRows[0] === ["test@example.org", "A", 0, "X", "Alles bestens"],
    "SurveySubmissionModel::yieldExcelRowsByTopic() verschiebt E-Mail an erste Spalte für den Excel-Export"
);

$evalStatus = $dashboardModel->evaluateSurveyStatuses($submissionModel, "2026-11-19");
$check(
    $evalStatus["status"]["KVH"] === true
    && $evalStatus["surveyCount"]["KVH"] === 2
    && is_array($evalStatus["displayDate"]["KVH"])
    && $evalStatus["displayDate"]["KVH"]["datetime"] === "2026-11",
    "SurveyDashboardModel::evaluateSurveyStatuses() berechnet Erhebungsstatus, Differenz und Zähler für checkSurvey"
);

$startResult = $dashboardModel->startSurveys(null, ["KVH" => "2026-12-01", "LSH" => "2026-12-01"], "2026-12-02");
$check(
    in_array("KVH", $startResult["started"], true)
    && $startResult["status"]["KVH"] === "2026-12-01"
    && $startResult["diff"]["KVH"]["survey_stop"] === "2026-12-06",
    "SurveyDashboardModel::startSurveys() aktualisiert Start-/Stopp-Datum und Status für startSurvey"
);

$excelService = new DashboardExcelService();
$surveySpreadsheet = $excelService->buildSurveySpreadsheet($dashboardModel, $submissionModel, "1", [], "2026-12-01");
$kvhSheet = $surveySpreadsheet->getSheetByName("KVH");
$check(
    $surveySpreadsheet instanceof Spreadsheet
    && $kvhSheet !== null
    && $kvhSheet->getCell("A2")->getValue() === "test@example.org"
    && $kvhSheet->getCell("B2")->getValue() === "A",
    "DashboardExcelService::buildSurveySpreadsheet() erzeugt KVH-Worksheet mit Antworten ab Zeile 2"
);

$stopResult = $dashboardModel->stopSurveys(null, ["KVH" => "1"], $submissionModel, "2026-12-05");
$check(
    in_array("KVH", $stopResult["stopped"], true)
    && $stopResult["status"]["KVH"] === null
    && $stopResult["stoppedCount"]["KVH"] === 2
    && $stopResult["deletedCount"]["KVH"] === 2
    && $submissionModel->countByTopic(SurveyTopic::KVH) === 0,
    "SurveyDashboardModel::stopSurveys() setzt Erhebung zurück und löscht zugehörige Antworten über SurveySubmissionModel"
);

$tokenModel = new SurveyTokenModel($mockDb);
$upsertInitial = $tokenModel->upsertTokens(null, ["kvh" => "1", "lsh" => "1"], "unit-test-secret");
$upsertUpdate = $tokenModel->upsertTokens(null, ["kvh" => "1"], "unit-test-secret");
$check(
    $upsertInitial["insert"] === ["kvh", "lsh"]
    && $upsertUpdate["update"] === ["kvh"]
    && count(iterator_to_array($tokenModel->yieldAllTokens())) === 2,
    "SurveyTokenModel::upsertTokens() und yieldAllTokens() verwalten JWT-Tokens für Insert und Update"
);

$tokenSpreadsheet = $excelService->buildTokenSpreadsheet($tokenModel);
$activeTokenSheet = $tokenSpreadsheet->getActiveSheet();
$check(
    $activeTokenSheet->getCell("B2")->getValue() === "Befragung"
    && $activeTokenSheet->getCell("C2")->getValue() === "Token"
    && $activeTokenSheet->getCell("B3")->getValue() === "kvh",
    "DashboardExcelService::buildTokenSpreadsheet() baut formatierte Token-Tabelle ab Zelle B2 auf"
);

$reflectionPartarum = new ReflectionClass(Partarum::class);
/** @var Partarum $fakeBackend */
$fakeBackend = $reflectionPartarum->newInstanceWithoutConstructor();

$_SERVER["HTTP_AUTHORIZATION"] = "Bearer invalid-token";
ob_start();
$unauthCtrl = new DashboardApiController(
    DashboardApiAction::GetExcelData,
    backend: $fakeBackend,
    api: new API(),
    db: $mockDb,
    excelService: $excelService,
    authorizedOverride: false
);
$unauthCtrl->dispatch();
$unauthJson = (string)ob_get_clean();
$check(
    str_contains($unauthJson, "Your beer is stale") && http_response_code() === 403,
    "DashboardApiController verweigert unautorisierte Requests mit HTTP 403 und 'Your beer is stale'"
);

http_response_code(200);
ob_start();
$authDataCtrl = new DashboardApiController(
    DashboardApiAction::GetExcelData,
    backend: $fakeBackend,
    api: new API(),
    db: $mockDb,
    excelService: $excelService,
    authorizedOverride: true
);
$authDataCtrl->dispatch();
$authDataJson = (string)ob_get_clean();
$check(
    str_contains($authDataJson, '"survey_count"') && str_contains($authDataJson, '"ALL":0'),
    "DashboardApiController::dispatch(GetExcelData) liefert JSON mit survey_count"
);

$_POST = ["type" => "test", "all" => "1"];
ob_start();
$excelTestCtrl = new DashboardApiController(
    DashboardApiAction::GetExcelFile,
    backend: $fakeBackend,
    api: new API(),
    db: $mockDb,
    excelService: $excelService,
    authorizedOverride: true
);
$excelTestCtrl->dispatch();
$excelTestOut = (string)ob_get_clean();
$_POST = [];
$check(
    $excelTestOut === "hallo",
    "DashboardApiController::dispatch(GetExcelFile) unterstützt Vorab-Check mit type='test' ('hallo')"
);

echo str_repeat("-", 35) . PHP_EOL;
echo "App-Test Ergebnis: {$passed} erfolgreich, {$failed} fehlgeschlagen." . PHP_EOL;

exit($failed === 0 ? 0 : 1);
