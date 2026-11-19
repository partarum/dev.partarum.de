# `app/modules/dashboard/` – Dashboard-Modul (Auth, Excel & Stage-Enums)

## Fachliche Aufgaben des Dashboards

1. **Token für die Klienten erstellen (`/API/V1/dashboard/getToken` & `getTokenExcel`)**
   * Erzeugt JWT-Tokens je Befragung (`kvh`, `lsh`, `nsh`, `rhb`, `rhd`), speichert sie in der Tabelle `token` und stellt sie optional als formatierte Excel-Datei (`example.xlsx`) zum Download bereit.
2. **Monat & Zeitraum für die Befragungen festlegen und Erhebung starten (`/API/V1/dashboard/startSurvey`)**
   * Setzt das Startdatum (`survey_start`) einzeln oder für alle Befragungen gleichzeitig (`all`).
   * Berechnet automatisch das Enddatum (`survey_stop` anhand von `survey_max_diff`) sowie den Aktivitätsstatus (`survey_status`).
   * Solange `survey_start` gesetzt ist, sind die jeweiligen Befragungs-Routen (`/KVH` .. `/RHD`) geöffnet.
3. **Laufenden Status & Rückläufe überwachen (`/API/V1/dashboard/checkSurvey` & `getExcelData`)**
   * Prüft Tagesdifferenzen (`survey_diff`), aktualisiert `survey_status` und zählt die eingegangenen Antworten je Befragung (`surveyCount` / `survey_count`).
4. **Excel-Auswertung generieren und herunterladen (`/API/V1/dashboard/getExcelFile`)**
   * Erzeugt aus den JSON-Antworten (`survey_excel_character_content`) der Tabelle `survey` via `PhpSpreadsheet` eine mehrblättrige Arbeitsmappe (`Erhebung.xlsx`) mit je einem Worksheet pro ausgewähltem Topic.
5. **Erhebung beenden & Datenbank bereinigen (`/API/V1/dashboard/stopSurvey`)**
   * Setzt `survey_start`, `survey_stop` und `survey_status` in `survey_dashboard` zurück, protokolliert `survey_has_been_stopped` und löscht die abgegebenen Antworten des beendeten Zeitraums aus der Tabelle `survey`.

---

## Technische Klassen im Modul

| Datei / Klasse | Typ | Verantwortung |
| :--- | :--- | :--- |
| `DashboardStage.php` | `enum` (`string`) | Definiert die 4 Seiten-Zustände des Dashboards (`Login`, `Authenticate`, `Workspace`, `Logout`) inkl. Seitentitel und CSS-Pfad. |
| `DashboardApiAction.php` | `enum` (`string`) | Definiert die 7 API-Aktionen (`CheckSurvey`, `StartSurvey`, `StopSurvey`, `GetExcelData`, `GetExcelFile`, `GetToken`, `GetTokenExcel`) inkl. Partarum-App-Namen und 403-Fehlerpayload. |
| `DashboardAuthService.php` | `class` | Kapselt den 3-stufigen Cookie-/Session-Handshake (`beer`-Cookie auf `/loginPage`, Credential-Prüfung + JWT + `bearer`-Cookie auf `/dashboard`, `arrival`-Verifikation auf `/dashboardPage`). |
| `DashboardExcelService.php` | `class` | Erstellt die `PhpSpreadsheet\Spreadsheet`-Objekte für die Befragungsauswertung (`buildSurveySpreadsheet()`) und die Token-Tabelle (`buildTokenSpreadsheet()`) und streamt sie über `streamSpreadsheet()` an `php://output`. |