# `app/controllers/` – Request- & Stage-Controller

## Aufgabe

Der Ordner `app/controllers/` enthält die Steuerungsklassen der Anwendung. Jeder Controller implementiert das Interface `app\core\Contracts\ControllerInterface` (`dispatch(): void`) und übernimmt die Koordination zwischen Routing-Entrypoints (`app/pages/` bzw. `API/V1/dashboard/`), Models (`app/models/`), Services (`app/modules/`) und Layouts/Views (`app/layout/`, `app/views/`).

---

## Struktur & enthaltene Controller

```text
app/controllers/
├── Befragung/
│   └── SurveyStageController.php    # Steuert alle Befragungs-Phasen (Initial, Transition/Execution, Final)
└── dashboard/
    ├── DashboardApiController.php   # Verarbeitet alle 7 Dashboard-API-Endpunkte (/API/V1/dashboard/*)
    └── DashboardController.php      # Steuert Login, Authentifizierung, Workspace und Logout
```

### 1. `Befragung\SurveyStageController`
* Implementiert `ControllerInterface` und `RenderableInterface`.
* Wird in `app/pages/Befragung/*.php` über `SurveyStageController::handle(Surface::...)->dispatch()` aufgerufen.
* Löst über `SurfaceApp` und `SurveyTopic::fromCaseName()` das aktive Befragungsthema (`KVH`, `LSH`, `NSH`, `RHB`, `RHD`) auf.
* Prüft über `SurveyDashboardModel`, ob ein gültiges Startdatum (`survey_start`) vorliegt; falls nicht, erfolgt ein Redirect auf `/`.
* Verarbeitet in `Surface::FinalStage` die `$_POST`-Formulardaten über `SurveyFormListModel` und `SurveySubmissionModel`.
* Setzt je nach Stage das `OnboardingLayout` oder `ExecutionLayout` mit den passenden Komponenten und Views zusammen.

### 2. `dashboard\DashboardController`
* Implementiert `ControllerInterface` und `RenderableInterface`.
* Wird in `app/pages/dashboard/*.php` und `app/pages/dashboard.php` über `DashboardController::handle(DashboardStage::...)->dispatch()` aufgerufen.
* Nutzt `DashboardAuthService` für Session-/Cookie-Prüfungen und Redirects und rendert `LoginView` bzw. `DashboardWorkspaceView` im `DashboardLayout`.

### 3. `dashboard\DashboardApiController`
* Implementiert `ControllerInterface`.
* Wird in `API/V1/dashboard/*.php` über `DashboardApiController::handle(DashboardApiAction::...)->dispatch()` aufgerufen.
* Prüft den Bearer-Token im Redis-Cache (`Bearer:<token>`) und delegiert die fachliche Ausführung an `SurveyDashboardModel`, `SurveySubmissionModel`, `SurveyTokenModel` und `DashboardExcelService`.