# `app/` – Anwendungsschicht (Partarum Application Layer)

## Überblick & Architektur-Philosophie

Der Ordner `app/` bildet die fachliche **Anwendungsschicht** des Projekts. Er baut gezielt auf dem **Partarum-Framework** (`Partarum/`) auf (Routing, Surface-Stages, HTTP/API, JWT, Redis-Cache, Datenbank-Anbindung), bleibt jedoch als eigenständige Domänen- und Präsentationsschicht strikt vom Framework-Kern getrennt.

Alle Hauptseiten in `app/pages/` sowie alle Dashboard-API-Endpunkte in `API/V1/dashboard/` sind als **schlanke 1-Zeiler-Entrypoints** aufgebaut, die ihre Arbeit direkt an typsichere Controller delegieren.

### Technische Leitlinien (PHP 8.2+)
* **`declare(strict_types=1)`** in allen PHP-Dateien
* **Interfaces (`app/core/Contracts/`)** für klare Schicht-Verträge (`ControllerInterface`, `ModelInterface`, `RenderableInterface` sowie `PartarumSurface\Stages\*StageInterface`)
* **Backed Enums (`app/core/Enums/`, `app/modules/dashboard/`, `app/src/Befragung/`)** für Befragungsthemen (`SurveyTopic`), Surface-Stages (`Surface*Stage`) und Dashboard-Routen/-Aktionen (`DashboardStage`, `DashboardApiAction`)
* **Traits (`app/core/Traits/`)** für wiederkehrende Querschnittsaufgaben (`FeatHtmlEscape`, `FeatModelDatabase`)
* **Generatoren (`yield`)** für speichereffiziente Daten-Pipelines in den Models (z. B. `yieldMergedPostFields()`, `yieldExcelCharacters()`, `yieldExcelRowsByTopic()`, `yieldAllTopics()`)
* **Browser-Kompatibilität:**
  * Alles, was **Befragungen** für Endkunden rendert (`KVH`, `LSH`, `NSH`, `RHB`, `RHD`), bewahrt zu 100 % die **Internet-Explorer-kompatible** HTML- und CSS-Grid-Struktur (`-ms-grid-*`, `ie-main`, `pfm-wp-group`).
  * Das **Dashboard** wird modern über JavaScript/WebWorker (`PartarumJS`) und JSON/XLSX-Endpunkte betrieben.

---

## Verzeichnisstruktur

```text
app/
├── controllers/                # Request-Steuerung (HTTP-Pages & Dashboard-API)
│   ├── Befragung/
│   │   └── SurveyStageController.php
│   └── dashboard/
│       ├── DashboardApiController.php
│       └── DashboardController.php
├── core/                       # Gemeinsame Basis-Bausteine der App
│   ├── Contracts/              # ControllerInterface, ModelInterface, RenderableInterface
│   ├── Enums/                  # SurveyTopic (KVH, LSH, NSH, RHB, RHD)
│   ├── SurfaceExample/         # Beispiel-Stages für PartarumSurface
│   └── Traits/                 # FeatHtmlEscape, FeatModelDatabase
├── layout/                     # Seiten-Layouts (Document-Rahmen) & UI-Komponenten
│   ├── components/             # HeadComponent, StageHeaderComponent, ContactFooterComponent, SubmissionSectionComponent
│   ├── DashboardLayout.php
│   ├── ExecutionLayout.php
│   └── OnboardingLayout.php
├── models/                     # Datenbank-Interaktion (Medoo) & Generator-Pipelines
│   └── Befragung/
│       ├── SurveyDashboardModel.php
│       ├── SurveyFormListModel.php
│       ├── SurveySubmissionModel.php
│       └── SurveyTokenModel.php
├── modules/                    # Fachliche Services & Modul-Enums
│   ├── dashboard/
│   │   ├── DashboardApiAction.php
│   │   ├── DashboardAuthService.php
│   │   ├── DashboardExcelService.php
│   │   └── DashboardStage.php
│   └── SurfaceExample/
├── pages/                      # Schlanke 1-Zeiler-Entrypoints für das Partarum-Routing
│   ├── Befragung/              # InitialStage.php, KVH.php .. RHD.php, FinalStage.php
│   ├── dashboard/              # loginPage.php, logoutPage.php, dashboardPage.php
│   ├── dashboard.php
│   └── landingPage.html
├── src/                        # PartarumSurface-Anbindung für die Befragung
│   └── Befragung/              # SurfaceApp, Surface, Surface*Stage Enums
└── views/                      # Reine Darstellungs-Templates (RenderableInterface)
    ├── Befragung/
    │   ├── forms/              # IE-kompatible Fragebögen (KVHFormView .. RHDFormView)
    │   ├── FinalStageView.php
    │   └── InitialStageView.php
    └── dashboard/
        ├── DashboardWorkspaceView.php
        └── LoginView.php
```

---

## Zusammenspiel der Schichten (Request-Flows)

### 1. Befragungs-Flow (`KVH`, `LSH`, `NSH`, `RHB`, `RHD`)

Jede Befragung durchläuft drei Phasen (Stages), die über `app/src/Befragung/Surface` aufgelöst und durch `SurveyStageController` verarbeitet werden:

1. **InitialStage** (z. B. Aufruf `/KVH` -> `app/pages/Befragung/InitialStage.php`):
   * `SurveyStageController::handle(Surface::InitialStage)->dispatch()`
   * Prüft über `SurveyDashboardModel::getStartDate()`, ob die Erhebung für dieses Topic aktiv ist (falls `survey_start === NULL`, erfolgt ein Redirect auf `/`).
   * Rendert das `OnboardingLayout` mit `InitialStageView` und dem Start-Link (`KVH-Befragung?date=<Monat_Jahr>`).
2. **TransitionStage / ExecutionStage** (z. B. Aufruf `/KVH-Befragung` -> `app/pages/Befragung/KVH.php`):
   * `SurveyStageController::handle(Surface::TransitionStage)->dispatch()`
   * Rendert das `ExecutionLayout` mit der jeweiligen IE-kompatiblen Formular-View (`KVHFormView` .. `RHDFormView`) und der `SubmissionSectionComponent`.
3. **FinalStage** (z. B. POST auf `/KVH-Final` -> `app/pages/Befragung/FinalStage.php`):
   * `SurveyStageController::handle(Surface::FinalStage)->dispatch()`
   * Liest die Felddefinitionen über `SurveyFormListModel` aus `survey_form_list`, ergänzt nicht ausgefüllte Felder per Generator (`yieldMergedPostFields()`) und übersetzt Radio-/Textwerte in Excel-Spaltenwerte (`yieldExcelCharacters()`).
   * Speichert den Datensatz über `SurveySubmissionModel::saveSubmission()` in der Tabelle `survey` (inkl. Reload-Schutz bei leerem `$_POST`) und rendert das `OnboardingLayout` mit `FinalStageView`.

### 2. Dashboard-Seiten-Flow (`/loginPage`, `/dashboard`, `/dashboardPage`, `/logoutPage`)

* Alle Seiten in `app/pages/dashboard/` delegieren an `DashboardController::handle(DashboardStage::...)->dispatch()`.
* `DashboardAuthService` übernimmt das Session- und Cookie-Handshake-Verfahren (`beer` -> `bearer` -> `arrival`) sowie die Prüfung gegen `$_ENV["AUTH_USER"]` und `$_ENV["AUTH_PASSWORD"]`.
* `DashboardLayout` rendert `LoginView` bzw. `DashboardWorkspaceView`.

### 3. Dashboard-API-Flow (`API/V1/dashboard/*`)

Alle 7 Endpunkte unter `API/V1/dashboard/` delegieren in einer Zeile an `DashboardApiController::handle(DashboardApiAction::...)->dispatch()`:

| Endpunkt | Enum-Case (`DashboardApiAction`) | Genutzte Models / Services |
| :--- | :--- | :--- |
| `checkSurvey.php` | `DashboardApiAction::CheckSurvey` | `SurveyDashboardModel::evaluateSurveyStatuses()`, `SurveySubmissionModel` |
| `startSurvey.php` | `DashboardApiAction::StartSurvey` | `SurveyDashboardModel::startSurveys()` |
| `stopSurvey.php` | `DashboardApiAction::StopSurvey` | `SurveyDashboardModel::stopSurveys()`, `SurveySubmissionModel::deleteByTopic()` |
| `getExcelData.php` | `DashboardApiAction::GetExcelData` | `SurveySubmissionModel::getCountsSummary()` |
| `getExcelFile.php` | `DashboardApiAction::GetExcelFile` | `DashboardExcelService::buildSurveySpreadsheet()` |
| `getToken.php` | `DashboardApiAction::GetToken` | `SurveyTokenModel::upsertTokens()` |
| `getTokenExcel.php` | `DashboardApiAction::GetTokenExcel` | `DashboardExcelService::buildTokenSpreadsheet()` |

---

## Tests

Für `app/` existiert eine eigenständige Offline-Smoke-Test-Suite unter `cli/testApp.php` (getrennt vom Framework-Test `cli/testKernel.php`):

```bash
php cli/testApp.php
# oder über Composer:
composer test:app
```
