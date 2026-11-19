# `app/views/` – Darstellungs-Templates (Views)

## Aufgabe

Der Ordner `app/views/` enthält alle reinen Darstellungs-Klassen der Anwendung. Jede View implementiert `app\core\Contracts\RenderableInterface` (`render(): string`) und kümmert sich ausschließlich um die HTML-Ausgabe – ohne eigene Datenbankabfragen oder Session-Mutationen.

---

## Struktur

```text
app/views/
├── Befragung/
│   ├── forms/                      # IE-kompatible Fragebögen für die ExecutionStage
│   │   ├── KVHFormView.php         # Konstruktionsvollholz
│   │   ├── LSHFormView.php         # Laubschnittholz
│   │   ├── NSHFormView.php         # Nadelschnittholz
│   │   ├── RHBFormView.php         # Rohholz Buchen
│   │   └── RHDFormView.php         # Rohholz Douglasie
│   ├── FinalStageView.php          # Bestätigungs-Inhalt nach erfolgreichem Absenden
│   └── InitialStageView.php        # Startseiten-Inhalt einer Befragung mit Link zur ExecutionStage
└── dashboard/
    ├── DashboardWorkspaceView.php  # Hauptoberfläche des Dashboards (/dashboardPage)
    └── LoginView.php               # Login-Formular (/loginPage & /logoutPage)
```

---

## Besonderheit: Internet-Explorer-Kompatibilität in `Befragung/forms/`

Die 5 Fragebögen (`KVHFormView` bis `RHDFormView`) nutzen spezifische CSS-Grid- und Flexbox-Wrapper-Klassen (`pfm-area pre-form-area`, `pfm-wp-group`, `ie-legend`, `ie-grid-*`), die exakt auf die Stylesheets unter `/public/assets/css/themes/<TOPIC>/theme.css` und `/public/assets/css/partarumForm.css` abgestimmt sind, damit die Formulare auch im **Internet Explorer** pixelgenau dargestellt werden.

Dynamische Werte wie der aktuelle Berichtsmonat (`$displayDate`, z. B. bei `KVHFormView` in Frage 5 *„Produktionsmenge Fi/Ta NSi-KVH ...“*) werden typsicher in den Konstruktor übergeben und über `FeatHtmlEscape::escape()` ausgegeben.