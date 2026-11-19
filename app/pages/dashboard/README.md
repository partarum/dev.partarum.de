# `app/pages/dashboard/` – Dashboard-Seiten-Entrypoints

## Aufgabe

Dieser Ordner enthält die Routing-Entrypoints für die Benutzeroberfläche des Dashboards. Alle Dateien delegieren in einer Zeile an `app\controllers\dashboard\DashboardController`:

| Datei | Route | `DashboardStage`-Case | Beschreibung |
| :--- | :--- | :--- | :--- |
| `loginPage.php` | `/loginPage` | `DashboardStage::Login` | Setzt bestehende Sessions zurück, setzt das `beer`-Handshake-Cookie und rendert die `LoginView`. |
| `../dashboard.php` | `/dashboard` | `DashboardStage::Authenticate` | Prüft `beer`-Cookie und POST-Logindaten (`user`, `password`), erzeugt Session + `bearer`-Cookie und leitet auf `/dashboardPage` (bzw. bei Fehler auf `/loginPage`) weiter. |
| `dashboardPage.php` | `/dashboardPage` | `DashboardStage::Workspace` | Prüft `bearer`-Cookie gegen die Session, setzt das `arrival`-Cookie und rendert die `DashboardWorkspaceView`. |
| `logoutPage.php` | `/logoutPage` | `DashboardStage::Logout` | Zerstört die aktive Session, stellt ein frisches `beer`-Cookie aus und zeigt wieder die `LoginView`. |

Die fachliche Beschreibung und die zugehörigen Services (`DashboardAuthService`, `DashboardExcelService`, `DashboardApiAction`) befinden sich unter [`app/modules/dashboard/README.md`](../../modules/dashboard/README.md).