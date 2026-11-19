# `app/layout/components/` – Wiederverwendbare Layout-Komponenten

## Aufgabe

Dieser Ordner enthält gekapselte, wiederverwendbare HTML-Komponenten, die in den Seiten-Layouts (`OnboardingLayout`, `ExecutionLayout`, `DashboardLayout`) zusammengesetzt werden. Jede Komponente implementiert `app\core\Contracts\RenderableInterface` und nutzt `app\core\Traits\FeatHtmlEscape` für sicheres UTF-8-Escaping.

---

## Komponenten

| Klasse | Beschreibung |
| :--- | :--- |
| `HeadComponent` | Rendert den `<head>`-Bereich inklusive `<meta charset="utf-8">`, Viewport, `<title>` und einer Liste von Stylesheet-Definitionen (`href`, optional `type` und `media`). |
| `StageHeaderComponent` | Rendert den `<header>` der Befragungsseiten mit Titel (`<h1>`), Berichtsmonat (`<time datetime="YYYY-MM">Monat Jahr</time>`) und dem Waldbesitzer-/Sägeindustrie-Logo. Unterstützt sowohl den Onboarding-Header als auch den `pfmExecutionHeader` im Fragebogen. |
| `ContactFooterComponent` | Rendert den `<footer>` der Befragungen mit der jeweiligen Verbände-Zeile (`SurveyTopic::getPartnerString()`), den Kontaktdaten (Franz-Josef Lückge) und optional dem Internet-Explorer-/E-Mail-Hinweistext (`#ieInfoText`). |
| `SubmissionSectionComponent` | Rendert den unteren Formular-Abschnitt (`#pfmFinalStage`) aller 5 Fragebögen mit dem optionalen E-Mail-Eingabefeld (`e_1_n_1`) und dem Submit-Button (`Daten absenden`). |