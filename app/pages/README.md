# `app/pages/` – Routing-Entrypoints der Anwendung

## Aufgabe

Der Ordner `app/pages/` enthält die Ziel-Dateien des Partarum-Routers (`config/surface-manifest.json`, `config/pages-manifest.json`).

In der aufgeräumten `app/`-Architektur enthalten diese Dateien **keine eigene Geschäftslogik, Datenbankabfragen oder HTML-Blöcke mehr**, sondern fungieren als schlanke **1-Zeiler-Entrypoints**, die direkt an den zuständigen Controller (`SurveyStageController` oder `DashboardController`) übergeben.

---

## Struktur & Zuordnung

```text
app/pages/
├── Befragung/
│   ├── InitialStage.php       # -> SurveyStageController::handle(Surface::InitialStage)->dispatch()
│   ├── KVH.php                # -> SurveyStageController::handle(Surface::TransitionStage)->dispatch()
│   ├── LSH.php                # -> SurveyStageController::handle(Surface::TransitionStage)->dispatch()
│   ├── NSH.php                # -> SurveyStageController::handle(Surface::TransitionStage)->dispatch()
│   ├── RHB.php                # -> SurveyStageController::handle(Surface::TransitionStage)->dispatch()
│   ├── RHD.php                # -> SurveyStageController::handle(Surface::TransitionStage)->dispatch()
│   └── FinalStage.php         # -> SurveyStageController::handle(Surface::FinalStage)->dispatch()
├── dashboard/
│   ├── loginPage.php          # -> DashboardController::handle(DashboardStage::Login)->dispatch()
│   ├── dashboardPage.php      # -> DashboardController::handle(DashboardStage::Workspace)->dispatch()
│   ├── logoutPage.php         # -> DashboardController::handle(DashboardStage::Logout)->dispatch()
│   └── README.md
├── dashboard.php              # -> DashboardController::handle(DashboardStage::Authenticate)->dispatch()
└── landingPage.html           # Statische Startseite (/)
```
