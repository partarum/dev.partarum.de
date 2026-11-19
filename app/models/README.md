# `app/models/` – Datenmodelle, Datenbank-Interaktion & Generator-Pipelines

## Aufgabe

Der Ordner `app/models/` kapselt sämtliche Datenbankzugriffe (über `Medoo\Medoo`) sowie die Datentransformationen der Anwendung. Weder Controller noch Views oder API-Skripte greifen direkt per SQL/Medoo auf Tabellen zu.

Alle Models implementieren `app\core\Contracts\ModelInterface` und binden das Trait `app\core\Traits\FeatModelDatabase` ein. Für Iterationen über Datensätze, Formularfelder und Excel-Spaltenwerte nutzen die Models speicherschonende PHP-**Generatoren (`yield`)**.

---

## Struktur & Models

```text
app/models/
└── Befragung/
    ├── SurveyDashboardModel.php    # Tabelle `survey_dashboard` (Start/Stopp, Status, Zeitfenster)
    ├── SurveyFormListModel.php     # Tabelle `survey_form_list` (Feldschemata & Excel-Zeichen-Mapping)
    ├── SurveySubmissionModel.php   # Tabelle `survey` (Speichern, Zählen, Löschen & Excel-Zeilen)
    └── SurveyTokenModel.php        # Tabelle `token` (JWT-Generierung, Insert/Update & Auslesen)
```

### 1. `Befragung\SurveyDashboardModel` (Tabelle `survey_dashboard`)
* `getStartDate(SurveyTopic $topic): ?string` – Liest `survey_start` für ein Befragungsthema.
* `getFormattedDateInfo(SurveyTopic $topic): ?array` – Formatiert das Startdatum über `Surface::getDate()` in `date`, `datetime` und `displayDate`.
* `yieldAllTopics(): Generator` – Iteriert als Generator über alle 5 `SurveyTopic`-Datensätze.
* `yieldStartDates(): Generator` – Liefert per `yield` die Startdaten aller Topics.
* `evaluateSurveyStatuses(SurveySubmissionModel $submissionModel, ?string $referenceDate = null): array` – Berechnet die Tagesdifferenz (`survey_diff`), prüft das erlaubte Fenster (`survey_max_diff`), aktualisiert `survey_status` in der Datenbank und liefert das Antwort-Payload für `/API/V1/dashboard/checkSurvey`.
* `startSurveys(mixed $allDate, array $topicDates, ?string $referenceDate = null): array` – Setzt Start- und berechnetes Stoppdatum (`survey_start`, `survey_stop`, `survey_has_been_set`) für `/API/V1/dashboard/startSurvey`.
* `stopSurveys(mixed $allFlag, array $selectedTopics, SurveySubmissionModel $submissionModel, ?string $referenceDate = null): array` – Beendet ausgewählte Erhebungen und bereinigt über `SurveySubmissionModel::deleteByTopic()` die zugehörigen Einträge in `survey` (`/API/V1/dashboard/stopSurvey`).

### 2. `Befragung\SurveyFormListModel` (Tabelle `survey_form_list`)
* `getConfiguration(SurveyTopic $topic): array` – Liest und dekodiert `survey_fields`, `survey_areas`, `survey_sections` und `survey_content` **NULL-sicher** (verhindert PHP 8.1+ `json_decode(null)` Deprecation-Warnungen, wenn z. B. `survey_sections` bei `KVH` in der Datenbank `NULL` ist).
* `yieldMergedPostFields(SurveyTopic $topic, array $postData): Generator` – Ergänzt fehlende Formularfelder im `$_POST`-Array per Generator mit dem Defaultwert `0`.
* `yieldExcelCharacters(SurveyTopic $topic, array $postData): Generator` & `buildExcelCharacterArray(...)` – Übersetzt Radio-Werte (`100`, `010`, ...) in Buchstaben (`A`, `B`, ...), nicht gesetzte Optionen in `0`, leere Textfelder in `"X"` und ausgefüllte Text-/Zahlenfelder in ihren Wert.

### 3. `Befragung\SurveySubmissionModel` (Tabelle `survey`)
* `saveSubmission(SurveyTopic $topic, array $postData, array $excelCharacterArray): bool` – Speichert eine abgeschlossene Befragung als JSON in `survey` (ignoriert leere Requests zum Schutz vor F5-Reload-Duplikaten).
* `countByTopic(SurveyTopic $topic): int`, `yieldCountsByTopic(): Generator` und `getCountsSummary(): array` – Zählt eingegangene Befragungen je Topic sowie insgesamt (`ALL`) für `/API/V1/dashboard/getExcelData`.
* `deleteByTopic(SurveyTopic $topic): int` – Löscht alle Antworten eines Topics beim Beenden der Erhebung.
* `yieldExcelRowsByTopic(SurveyTopic $topic): Generator` – Dekodiert `survey_excel_character_content` zeilenweise als Generator und schiebt das letzte Feld (E-Mail-Adresse) für den Excel-Export an die erste Spalte.

### 4. `Befragung\SurveyTokenModel` (Tabelle `token`)
* `upsertTokens(mixed $allFlag, array $selectedTopics, string $secretKey): array` – Erstellt JWT-Tokens für ausgewählte Befragungen und führt je nach Vorhandensein ein `update` oder `insert` in `token` durch (`/API/V1/dashboard/getToken`).
* `yieldAllTokens(): Generator` – Liefert alle gespeicherten Tokens (`name`, `value`) als Generator für den Excel-Export (`/API/V1/dashboard/getTokenExcel`).