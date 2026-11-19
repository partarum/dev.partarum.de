# `app/core/` – Kern-Verträge, Enums & Traits der App

## Aufgabe

Der Ordner `app/core/` bündelt schichtübergreifende Grundlagen der Anwendung (`Contracts`, `Enums`, `Traits`), die von Controllern, Models, Layouts und Views gemeinsam genutzt werden.

---

## Struktur

```text
app/core/
├── Contracts/
│   ├── ControllerInterface.php   # Vertrag für alle Controller: dispatch(): void
│   ├── ModelInterface.php        # Vertrag für alle Datenbank-Models: getDatabase(): Medoo
│   └── RenderableInterface.php   # Vertrag für alle Views, Komponenten & Layouts: render(): string
├── Enums/
│   └── SurveyTopic.php           # Backed Enum (KVH, LSH, NSH, RHB, RHD) inkl. Metadaten & View-Factory
├── SurfaceExample/               # Referenz-/Beispiel-Enums für PartarumSurface-Stages
└── Traits/
    ├── FeatHtmlEscape.php        # HTML-Escaping-Helper escape(string): string (UTF-8, ENT_QUOTES)
    └── FeatModelDatabase.php     # Stellt protected Medoo $db und getDatabase(): Medoo bereit
```

---

## Zentrale Bausteine

### `Contracts\RenderableInterface`
Jede View (`app/views/`), jede Layout-Komponente (`app/layout/components/`) und jedes Seiten-Layout (`app/layout/`) implementiert `render(): string`. Dadurch wird HTML erst beim finalen `dispatch()` im Controller ausgegeben und lässt sich in Tests (`cli/testApp.php`) ohne Output-Buffering direkt als String prüfen.

### `Enums\SurveyTopic`
Zentrales Backed Enum (`string`) für die fünf Befragungen:
* `SurveyTopic::KVH` (*Konstruktionsvollholz*)
* `SurveyTopic::LSH` (*Laubschnittholz*)
* `SurveyTopic::NSH` (*Nadelschnittholz*)
* `SurveyTopic::RHB` (*Rohholz Buchen*)
* `SurveyTopic::RHD` (*Rohholz Douglasie*)

Stellt typsichere Methoden für Fachtitel (`getTitle()`), Kooperationspartner-Footers (`getPartnerString()`), Theme-Stylesheets (`getThemeStylesheet()`), Ziel-URLs (`getExecutionUrl()`, `getFinalActionUrl()`), IE-spezifische CSS-Klassen (`getExecutionMainClass()`) sowie die Formular-View-Erzeugung (`createFormView()`) bereit.