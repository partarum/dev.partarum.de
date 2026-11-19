# `app/layout/` – Seiten-Layouts & Wiederverwendbare Layout-Komponenten

## Aufgabe

Der Ordner `app/layout/` definiert die äußeren HTML5-Dokumentrahmen (`<!DOCTYPE html> ... </html>`) der Anwendung und setzt diese aus wiederverwendbaren UI-Komponenten (`app/layout/components/`) und einer injizierten Haupt-View (`RenderableInterface`) zusammen.

Alle Layouts implementieren `app\core\Contracts\RenderableInterface`.

---

## Struktur

```text
app/layout/
├── components/
│   ├── ContactFooterComponent.php      # Footer mit Ansprechpartner, Partner-Logo & optionalem IE-Hinweis
│   ├── HeadComponent.php               # <head> mit Charset, Viewport, Title und Stylesheet-Links
│   ├── StageHeaderComponent.php        # <header> mit Logo, Befragungstitel und <time>-Berichtsmonat
│   └── SubmissionSectionComponent.php  # Abschlussbereich der Fragebögen (E-Mail-Feld & Absenden-Button)
├── DashboardLayout.php                 # HTML5-Rahmen für Login- und Dashboard-Workspace-Seiten
├── ExecutionLayout.php                 # IE-kompatibler HTML5-Rahmen für die aktive Befragung (<form>)
└── OnboardingLayout.php                # IE-kompatibler HTML5-Rahmen für InitialStage und FinalStage
```

---

## Layouts im Überblick

1. **`OnboardingLayout`**:
   * Wird für die Startseite einer Befragung (`InitialStage`) sowie die Bestätigungsseite (`FinalStage`) verwendet.
   * Kombiniert `HeadComponent`, `StageHeaderComponent`, die jeweilige Stage-View (`InitialStageView` oder `FinalStageView`) und `ContactFooterComponent`.
   * Unterstützt optionale Inline-Grid-Styles (`-ms-grid-rows: ...`) am `<body>` für volle Internet-Explorer-Kompatibilität.
2. **`ExecutionLayout`**:
   * Umhüllt den eigentlichen Fragebogen (`KVHFormView` .. `RHDFormView`) und die `SubmissionSectionComponent` mit dem `<form method="post" action="./<TOPIC>-Final">`-Container und den IE-spezifischen `<main>`-Klassen.
3. **`DashboardLayout`**:
   * Schlanker HTML5-Rahmen für die modernen Dashboard-Seiten (`LoginView` und `DashboardWorkspaceView`).