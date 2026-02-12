```
public/
├── assets/
│   ├── modules/
│   │   ├── Dashboard/
│   │   │   └── css/
│   │   │       ├── base/
│   │   │       │   ├── _variables.scss
│   │   │       │   ├── _fonts.scss
│   │   │       │   ├── base.scss
│   │   │       │   └── base.css         // Kompilierte CSS-Datei
│   │   │       ├── components/
│   │   │       │   └── README.md        // (Leerer Ordner, nur README)
│   │   │       ├── layout/
│   │   │       │   └── README.md        // (Leerer Ordner, nur README)
│   │   │       └── style.css            // Leere CSS-Datei (wahrscheinlich für Kompilat gedacht)
│   │   ├── Erhebung/
│   │   │   └── css/
│   │   │       ├── base/
│   │   │       │   ├── _variables.scss
│   │   │       │   ├── _fonts.scss      // Leere SCSS-Datei
│   │   │       │   ├── base.scss
│   │   │       │   └── base.css         // Kompilierte CSS-Datei
│   │   │       ├── components/
│   │   │       │   ├── components.scss
│   │   │       │   └── components.css   // Kompilierte CSS-Datei
│   │   │       ├── layout/
│   │   │       │   ├── layout.scss
│   │   │       │   └── layout.css       // Kompilierte CSS-Datei
│   │   │       ├── themes/
│   │   │       │   ├── KVH/
│   │   │       │   │   ├── theme.scss
│   │   │       │   │   └── theme.css    // Kompilierte CSS-Datei
│   │   │       │   ├── LSH/
│   │   │       │   │   ├── theme.scss
│   │   │       │   │   └── theme.css    // Kompilierte CSS-Datei
│   │   │       │   ├── NSH/
│   │   │       │   │   ├── theme.scss
│   │   │       │   │   └── theme.css    // Kompilierte CSS-Datei
│   │   │       │   ├── RHB/
│   │   │       │   │   ├── theme.scss
│   │   │       │   │   └── theme.css    // Kompilierte CSS-Datei
│   │   │       │   └── RHD/
│   │   │       │       ├── theme.scss
│   │   │       │       └── theme.css    // Kompilierte CSS-Datei
│   │   │       ├── style.scss           // Leere SCSS-Datei
│   │   │       └── style.css            // Kompilierte CSS (mit Import-Fehler)
│   ├── shared/
│   │   ├── css/  // Dieser Ordner wird primär umstrukturiert
│   │   │   ├── base/
│   │   │   │   ├── _fonts.scss
│   │   │   │   ├── base.scss          // Leere SCSS-Datei
│   │   │   │   └── base.css           // Kompilierte, leere CSS-Datei
│   │   │   ├── components/
│   │   │   │   ├── components.scss    // Leere SCSS-Datei
│   │   │   │   └── components.css     // Kompilierte, leere CSS-Datei
│   │   │   ├── layout/
│   │   │   │   ├── layout.scss        // Leere SCSS-Datei
│   │   │   │   └── layout.css         // Kompilierte, leere CSS-Datei
│   │   │   ├── structurte_files.md    // Deine Notizen zur Struktur
│   │   │   ├── structure_modules.md   // Deine Notizen zur Struktur
│   │   │   └── structure.md           // Deine Notizen zur Struktur
│   │   ├── fonts/
│   │   │   ├── delight_snowy/         // (Font-Dateien)
│   │   │   ├── fontawesome/           // FontAwesome Paket
│   │   │   │   ├── css/               // (FA CSS-Dateien)
│   │   │   │   ├── scss/              // FontAwesome SCSS-Partials (bleiben hier)
│   │   │   │   │   ├── _variables.scss
│   │   │   │   │   ├── _mixins.scss
│   │   │   │   │   ├── fontawesome.scss
│   │   │   │   │   └── ... (weitere FA SCSS-Dateien)
│   │   │   │   └── webfonts/          // (FA Webfont-Dateien)
│   │   │   └── JetBrainsMono-2.304/   // (Font-Dateien)
```

```
public/
├── assets/
│   ├── modules/
│   │   ├── Dashboard/
│   │   │   └── scss/  // Umbenannt von css/, neue Struktur
│   │   │       ├── base/
│   │   │       │   ├── _variables.scss // Dashboard-spezifische Variablen
│   │   │       │   └── _base.scss      // Dashboard-spezifische Basis-Styles
│   │   │       ├── components/
│   │   │       │   └── _beispiel-dashboard-komponente.scss // Beispiel für eine spezifische Komponente
│   │   │       ├── layout/
│   │   │       │   └── _dashboard-layout.scss // Beispiel für spezifisches Layout
│   │   │       └── dashboard.scss     // Haupt-SCSS für Dashboard: @use shared/*, @use module/*
│   │   ├── Erhebung/
│   │   │   └── scss/  // Umbenannt von css/, neue Struktur
│   │   │       ├── base/
│   │   │       │   ├── _variables.scss // Erhebung-spezifische Variablen
│   │   │       │   └── _base.scss      // Erhebung-spezifische Basis-Styles
│   │   │       ├── components/
│   │   │       │   └── _erhebungs-formular.scss // Beispiel, falls components.scss spezifische Inhalte hatte
│   │   │       ├── layout/
│   │   │       │   └── _erhebungs-seite.scss // Beispiel, falls layout.scss spezifische Inhalte hatte
│   │   │       ├── themes/            // Struktur für Themes bleibt ähnlich, aber als Partials
│   │   │       │   ├── KVH/
│   │   │       │   │   └── _theme.scss  // Jetzt ein Partial
│   │   │       │   ├── LSH/
│   │   │       │   │   └── _theme.scss
│   │   │       │   ├── NSH/
│   │   │       │   │   └── _theme.scss
│   │   │       │   ├── RHB/
│   │   │       │   │   └── _theme.scss
│   │   │       │   └── RHD/
│   │   │       │       └── _theme.scss
│   │   │       └── erhebung.scss      // Haupt-SCSS für Erhebung: @use shared/*, @use module/*, @use themes/*
│   ├── shared/
│   │   ├── scss/  // Umbenannt von css/, neue Struktur für geteilte SCSS-Dateien
│   │   │   ├── base/
│   │   │   │   ├── _variables.scss    // Globale SCSS-Variablen & CSS Custom Properties (konsolidiert)
│   │   │   │   ├── _fonts.scss        // Globale @font-face Regeln (konsolidiert)
│   │   │   │   ├── _reset.scss        // (Optional) CSS Reset
│   │   │   │   ├── _typography.scss   // Globale Typografie-Basis
│   │   │   │   └── _index.scss        // @forward für alle Partials im base/ Ordner
│   │   │   ├── components/
│   │   │   │   ├── _buttons.scss      // Beispiel: Button-Styles
│   │   │   │   ├── _cards.scss        // Beispiel: Card-Styles
│   │   │   │   ├── _forms.scss        // Beispiel: Formular-Styles
│   │   │   │   ├── _fontawesome.scss  // Konfiguration und Import für FontAwesome via @use
│   │   │   │   └── _index.scss        // @forward für alle Partials im components/ Ordner
│   │   │   ├── layout/
│   │   │   │   ├── _grid.scss         // Beispiel: Grid-System
│   │   │   │   ├── _main-structure.scss // Beispiel: Globale Seitenstruktur
│   │   │   │   └── _index.scss        // @forward für alle Partials im layout/ Ordner
│   │   │   ├── utils/
│   │   │   │   ├── _mixins.scss       // Globale Utility-Mixins
│   │   │   │   ├── _functions.scss    // Globale SCSS-Funktionen
│   │   │   │   └── _index.scss        // @forward für alle Partials im utils/ Ordner
│   │   │   └── main.scss            // Haupt-SCSS für geteilte Styles: @use base, components, layout, utils
│   │   │
│   │   ├── fonts/                     // Ordnerstruktur für Fonts bleibt bestehen
│   │   │   ├── delight_snowy/
│   │   │   ├── fontawesome/
│   │   │   │   ├── scss/              // FontAwesome SCSS-Partials (werden von shared/scss/components/_fontawesome.scss genutzt)
│   │   │   │   │   ├── _variables.scss
│   │   │   │   │   ├── _mixins.scss
│   │   │   │   │   ├── fontawesome.scss
│   │   │   │   │   └── ...
│   │   │   │   └── webfonts/
│   │   │   └── JetBrainsMono-2.304/
│   │   ├── structurte_files.md    // Notizen können zur Referenz bleiben
│   │   ├── structure_modules.md
│   │   └── structure.md
```

```
erhebung-projekt/               // Dein Projekt-Root-Verzeichnis
├── public/
│   └── assets/
│       ├── scss/                 // QUELLE: Deine SCSS-Dateien
│       │   ├── base/
│       │   │   ├── _variables.scss   // Projektglobale Variablen (Farben, Typo, Abstände)
│       │   │   ├── _fonts.scss       // @font-face Regeln (z.B. Narrenschiff, JetBrainsMono)
│       │   │   ├── _typography.scss  // Basis-Typografie (body, h1-h6, p, a etc.)
│       │   │   ├── _reset.scss       // (Optional) CSS-Reset oder Normalize
│       │   │   └── _index.scss       // Leitet alle Partials aus base/ weiter
│       │   │
│       │   ├── components/           // Wiederverwendbare UI-Komponenten
│       │   │   ├── _buttons.scss
│       │   │   ├── _cards.scss
│       │   │   ├── _forms.scss
│       │   │   ├── _fontawesome.scss // Konfiguration & Import für FontAwesome
│       │   │   └── _index.scss       // Leitet alle Partials aus components/ weiter
│       │   │
│       │   ├── layout/               // Layout-Strukturen
│       │   │   ├── _main-content.scss
│       │   │   ├── _header.scss
│       │   │   ├── _footer.scss
│       │   │   └── _index.scss       // Leitet alle Partials aus layout/ weiter
│       │   │
│       │   ├── pages/                // Styles für spezifische Seiten/"interne Module"
│       │   │   ├── _startpage.scss
│       │   │   ├── _finalpage.scss
│       │   │   ├── _questionnaire.scss
│       │   │   └── _index.scss       // Leitet ggf. Page-Partials weiter
│       │   │
│       │   ├── themes/               // Theming (falls benötigt, z.B. für KVH, LSH)
│       │   │   ├── KVH/
│       │   │   │   └── _theme.scss
│       │   │   ├── LSH/
│       │   │   │   └── _theme.scss
│       │   │   └── _index.scss       // Leitet alle Themes weiter
│       │   │
│       │   ├── utils/                // SCSS Helfer: Mixins und Funktionen
│       │   │   ├── _mixins.scss
│       │   │   ├── _functions.scss
│       │   │   └── _index.scss       // Leitet alle Partials aus utils/ weiter
│       │   │
│       │   └── main.scss             // Haupteinstiegsdatei für das SCSS des Projekts
│       │
│       ├── css/                  // AUSGABE: Kompilierte CSS-Dateien
│       │   └── erhebung-main.css   // Wird aus public/assets/scss/main.scss generiert
│       │   └── ... (ggf. weitere CSS-Dateien für spezifische Themes oder Seiten)
│       │
│       ├── js/                   // QUELLE: Deine JavaScript-Dateien
│       │   ├── main.js
│       │   └── ...
│       │
│       ├── fonts/                // QUELLE: Schriftartendateien
│       │   ├── delight_snowy/      // Deine Projekt-Schriftarten
│       │   ├── JetBrainsMono-2.304/
│       │   └── fontawesome/        // FontAwesome Paket (falls manuell eingebunden)
│       │       ├── scss/           // Die .scss Dateien von FontAwesome
│       │       └── webfonts/       // Die Webfont-Dateien von FontAwesome
│       │
│       ├── media/                // QUELLE: Bilder, Videos etc.
│       │   ├── logo.png
│       │   └── ...
│
├── index.html                  // Oder andere HTML-/Template-Dateien im Root oder public/
├── package.json                // Falls du npm für Build-Tools wie Sass-Compiler, Autoprefixer nutzt
└── ... (weitere Projektdateien, z.B. Backend-Code, falls vorhanden)
```