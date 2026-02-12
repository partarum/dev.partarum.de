Ausgezeichnete Entscheidung\! Das wird die Struktur deines CSS deutlich verbessern. Gerne helfe ich dir dabei.

Lass uns das schrittweise angehen. Hier ist ein Plan, wie wir vorgehen könnten:

**Ziel:**

* partarumContainer.css minimieren oder ersetzen.  
* Layout-Container mit BEM-Syntax benennen.  
* CSS in separate Dateien für Basis, Layout und Komponenten aufteilen.  
* Die alten CSS-Dateien (dashboard\_new.css, landingPage\_new.css) ersetzen.  
* dashboardPage.php entsprechend anpassen.

**Schritt 1: Neue Dateistruktur vorschlagen**

Wir könnten eine klarere Ordnerstruktur unter /public/assets/css/ einführen:

```
/public/assets/css/  
├── base/  
│   ├── \_reset.css        \# (Optional) CSS Reset  
│   ├── \_variables.css    \# CSS Variablen (:root)  
│   ├── \_fonts.css        \# @font-face Regeln  
│   └── base.css          \# Basis-Styling (body, Links etc.) & @imports für andere base-Dateien  
├── layout/  
│   ├── \_main-layout.css  \# Hauptseitenstruktur (ersetzt prt-area etc.)  
│   └── layout.css        \# @imports für Layout-Dateien  
├── components/  
│   ├── \_main-nav.css     \# Hauptnavigation (aus dashboard\_new.css)  
│   ├── \_toolbar.css      \# Toolbars (aus landingPage\_new.css)  
│   ├── \_card.css         \# Karten-Komponente (aus landingPage\_new.css)  
│   ├── \_month-selector.css \# Monatsauswahl-Menü (aus dashboardPage.php)  
│   └── components.css    \# @imports für alle Komponenten  
└── style.css             \# Haupt-CSS-Datei, die base.css, layout.css, components.css importiert

```

*(Die Unterstriche bei den Dateinamen sind eine Konvention, um "Partials" zu kennzeichnen, die importiert werden sollen, aber das ist optional).*

**Schritt 2: Basis-Styles extrahieren (base/)**

* Wir nehmen die @font-face-Regeln aus dashboard\_new.css und verschieben sie nach base/\_fonts.css.  
* Wir sammeln alle :root-Variablen aus dashboard\_new.css und landingPage\_new.css und legen sie in base/\_variables.css ab.  
* Wir verschieben die Basis body-Styles (Hintergrund, Schriftart) aus dashboard\_new.css nach base/base.css.

**Schritt 3: Layout neu definieren (layout/)**

* Wir definieren neue BEM-Klassen für die Haupt-Layout-Container in dashboardPage.php. Statt prt-area, prt-area-main etc. könnten wir z.B. verwenden:  
  * body \-\> .page-container (als Haupt-Container)  
  * header \-\> .page-header  
  * main \-\> .page-content (als Container für den Hauptinhalt)  
  * footer \-\> .page-footer  
  * section (direkt unter main) \-\> .content-section oder spezifischer, z.B. .dashboard-controls, .dashboard-io  
* In layout/\_main-layout.css definieren wir diese Klassen und machen die gewünschten davon zu CSS-Containern (z.B. .page-container, .page-content).  
* Wir übertragen die Grid-Layouts und die *obersten* Container Queries aus landingPage\_new.css, die das Hauptlayout steuern, in diese Datei und passen sie an die neuen BEM-Klassen und Container-Namen an.

**Schritt 4: Komponenten extrahieren (components/)**

* **Hauptnavigation:** Wir erstellen components/\_main-nav.css. Alle Styles für header.navigation, \#menuButton, Animationen etc. aus dashboard\_new.css kommen hierher. Wir ersetzen die IDs und generischen Klassen durch BEM-Namen wie .main-nav, .main-nav\_\_toggle, .main-nav\_\_list, .main-nav--open.  
* **Toolbar:** Styles für .prt-toolbox aus landingPage\_new.css kommen nach components/\_toolbar.css mit BEM-Namen wie .toolbar.  
* **Card:** Styles für .card aus landingPage\_new.css kommen nach components/\_card.css (z.B. als .info-card).  
* **Monatsauswahl:** Styles für das \<menu\> mit den Monatsbuttons aus dashboardPage.php kommen nach components/\_month-selector.css.

**Schritt 5: HTML (dashboardPage.php) anpassen**

* Wir ersetzen die alten prt- Klassen durch die neuen BEM-Layout- und Komponentenklassen.  
* Wir aktualisieren die \<link\>-Tags im \<head\>: Entfernen die alten CSS-Dateien und fügen einen Link zur neuen Hauptdatei (style.css oder direkt zu base.css, layout.css, components.css) hinzu.  
* Wir korrigieren/entfernen den fehlerhaften Inline-\<style\>-Block.

**Schritt 6: Aufräumen**

* Löschen von dashboard\_new.css und landingPage\_new.css.  
* Überprüfen von partarumContainer.css: Was davon wird noch gebraucht? Wahrscheinlich kann es stark reduziert oder ganz entfernt werden.

**Wie möchtest du starten?**

Sollen wir mit **Schritt 1 und 2** beginnen (Struktur anlegen, Basis-Styles extrahieren) oder direkt mit **Schritt 3** (Layout-Container mit BEM benennen und definieren)?