```
public/
└── assets/
    ├── modules/
    │   ├── Dashboard/
    │   │   └── css/
    │   │       ├── base/
    │   │       │   ├── _fonts.scss
    │   │       │   └── _variables.scss
    │   │       │   └── base.scss
    │   │       └── (style.scss - Annahme: fehlend oder nicht im Upload, aber benötigt für style.css)
    │   │
    │   └── Erhebung/
    │       └── css/
    │           ├── base/
    │           │   ├── _fonts.scss
    │           │   ├── _variables.scss
    │           │   └── base.scss
    │           ├── components/
    │           │   └── components.scss
    │           ├── layout/
    │           │   └── layout.scss
    │           ├── themes/
    │           │   ├── KVH/
    │           │   │   └── theme.scss
    │           │   ├── LSH/
    │           │   │   └── theme.scss
    │           │   ├── NSH/
    │           │   │   └── theme.scss
    │           │   ├── RHB/
    │           │   │   └── theme.scss
    │           │   └── RHD/
    │           │       └── theme.scss
    │           └── style.scss  // Haupt-SCSS für Modul Erhebung
    │
    └── shared/
        ├── css/  // Enthält SCSS-Partials, was unkonventionell ist
        │   ├── base/
        │   │   ├── _fonts.scss
        │   │   └── base.scss
        │   ├── components/
        │   │   └── components.scss
        │   └── layout/
        │       └── layout.scss
        │
        ├── fonts/
        │   └── fontawesome/
        │       └── scss/       // Kompletter FontAwesome SCSS Ordner
        │           ├── _animated.scss
        │           ├── _bordered-pulled.scss
        │           ├── _core.scss
        │           ├── _fixed-width.scss
        │           ├── _functions.scss
        │           ├── _icons.scss
        │           ├── _list.scss
        │           ├── _mixins.scss
        │           ├── _rotated-flipped.scss
        │           ├── _screen-reader.scss
        │           ├── _shims.scss
        │           ├── _sizing.scss
        │           ├── _stacked.scss
        │           ├── _variables.scss
        │           ├── fontawesome.scss
        │           └── regular.scss
        │           └── ... (weitere FontAwesome SCSS-Dateien)
        │
        ├── scss/               // Haupt-SCSS-Datei für "Frontend"
        │   └── input.scss
        │
        └── scss_dashboard/     // Haupt-SCSS-Datei für Dashboard
            └── dashboard.scss
```

```
public/
└── assets/
    ├── scss/  // NEUER ZENTRALER SCSS ORDNER
    │   ├── abstracts/  (oder utils/)
    │   │   ├── _variables.scss    // Globale Variablen (Farben, Typo-Skalen, Breakpoints)
    │   │   ├── _mixins.scss       // Globale Mixins (Media Queries, Helfer)
    │   │   ├── _functions.scss    // Globale Sass-Funktionen (optional)
    │   │   └── _index.scss        // @forwardet alles aus abstracts/
    │   │
    │   ├── base/
    │   │   ├── _reset.scss        // Optionaler Reset/Normalize
    │   │   ├── _fonts.scss        // @font-face Definitionen (aus shared/css/base/_fonts.scss)
    │   │   ├── _typography.scss   // Basis-Typografie (body, h1-h6, p, a)
    │   │   ├── _base.scss         // Allgemeine HTML-Element-Defaults (aus shared/css/base/base.scss)
    │   │   └── _index.scss        // @forwardet alles aus base/
    │   │
    │   ├── layout/
    │   │   ├── _grid.scss         // Beispiel für Grid-System
    │   │   ├── _header.scss       // Stile für den Header
    │   │   ├── _footer.scss       // Stile für den Footer
    │   │   ├── _main-content.scss // Stile für Hauptinhaltsbereiche
    │   │   └── _index.scss        // @forwardet alles aus layout/
    │   │                          // (Inhalte aus shared/css/layout/layout.scss hierher migrieren)
    │   │
    │   ├── components/
    │   │   ├── _buttons.scss
    │   │   ├── _cards.scss
    │   │   ├── _forms.scss
    │   │   └── _index.scss        // @forwardet alles aus components/
    │   │                          // (Inhalte aus shared/css/components/components.scss hierher migrieren)
    │   │
    │   ├── themes/                // Optional, für globale Themes
    │   │   ├── _default-theme.scss
    │   │   └── _index.scss
    │   │
    │   ├── vendors/
    │   │   ├── fontawesome/
    │   │   │   └── _index.scss     // @forwardet die fontawesome.scss aus shared/fonts/
    │   │   └── _index.scss        // @forwardet alle vendor-indices
    │   │
    │   ├── main.scss              // Neuer Name für input.scss (oder input.scss beibehalten)
    │   └── dashboard-styles.scss  // Neuer Name für dashboard.scss (oder dashboard.scss beibehalten)
    │
    ├── modules/
    │   ├── Dashboard/
    │   │   └── css/ // Oder besser: scss/
    │   │       ├── base/ // Kann bleiben für Dashboard-spezifische Basis-Anpassungen/Variablen
    │   │       │   ├── _variables.scss // Dashboard-spezifische Variablen, die globale überschreiben/ergänzen
    │   │       │   └── ...
    │   │       ├── components/
    │   │       │   └── _dashboard-specific-component.scss
    │   │       ├── layout/
    │   │       │   └── _dashboard-specific-layout.scss
    │   │       └── style.scss          // Haupt-SCSS für Modul Dashboard (verwendet shared SCSS und eigene Partials)
    │   │
    │   └── Erhebung/
    │       └── css/ // Oder besser: scss/
    │           ├── base/ // Bleibt für Erhebungs-spezifische Basis-Anpassungen/Variablen
    │           │   ├── _variables.scss // Erhebungs-spezifische Variablen
    │           │   └── ...             // (Inhalt von altem _fonts.scss & base.scss ggf. prüfen, ob global oder hier spezifisch)
    │           ├── components/
    │           │   ├── _erhebung-specific-component.scss
    │           │   └── ...             // (Inhalt von altem components.scss hierher als Partials)
    │           ├── layout/
    │           │   ├── _erhebung-specific-layout.scss
    │           │   └── ...             // (Inhalt von altem layout.scss hierher als Partials)
    │           ├── themes/             // Diese Struktur kann beibehalten werden
    │           │   ├── KVH/
    │           │   │   └── theme.scss
    │           │   ├── LSH/
    │           │   │   └── theme.scss
    │           │   └── ... (RHD etc.)
    │           └── style.scss          // Haupt-SCSS für Modul Erhebung (verwendet shared SCSS und eigene Partials)
    │
    └── shared/
        ├── fonts/
        │   └── fontawesome/
        │       └── scss/       // Bleibt als Quelle für FontAwesome, wird über /assets/scss/vendors/ eingebunden
        │
        ├── css/                // Dieser Ordner wird idealerweise leer oder stark reduziert,
        │                       // da SCSS-Partials nach assets/scss/ migriert werden.
        │
        ├── scss/               // Wird durch assets/scss/main.scss (o.Ä.) ersetzt.
        └── scss_dashboard/     // Wird durch assets/scss/dashboard-styles.scss (o.Ä.) ersetzt.

```