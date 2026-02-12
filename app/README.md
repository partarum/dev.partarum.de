
*   **/app/** *(Kern der Anwendung, geschützt)*
*   **/controllers/** *(Logik zur Verarbeitung von Anfragen)*
*   **/models/** *(Datenmodelle und Datenbankinteraktion)*
*   **/views/** *(Templates zur Darstellung der Daten)*
*   **/layout/** *(Layout-Komponenten und Templates)*
  *   `MainLayout.php` *(Hauptlayout der Anwendung)*
  *   **/components/** *(Wiederverwendbare Layout-Elemente)*
*   `Header.php`
*   `Footer.php`
*   **/modules/** (oder **/features** / **/apps**) *(Funktionale Module der Anwendung)*
  *   **/dashboard/**
  *   **/hmi/**
  *   **/landingpage/**
*   **/core/** (oder **/shared** / **/common**) *(Gemeinsame Funktionen und Komponenten)*
  *   **/auth/** *(Authentifizierung und Benutzerverwaltung)*
  *   **/components/** *(Wiederverwendbare UI-Komponenten)*
  *   **/services/** *(Logik, die von mehreren Modulen genutzt wird)*
  *   **/utils/** *(Hilfsfunktionen und -klassen)*

# app

## Beschreibung
Der Ordner "app" ist für Ausgabe - Scripte zuständig. 
Hier lagern die *.html Seiten oder PHP - Scripte welche HTML generieren.

Nicht zu verwechseln mit dem API - Ordner, 
dessen Aufgabengebiet die Kommunikation zwischen Client und Server ist.

## USER - Spezifisch

### Struktur

Vorweg:

Alles was nicht an den Endkunden geht, wird modern betrieben und alles was Befragungen etc. sind, wird Internet Explorer - Konform erledigt.

+ dashboard
+ landingPage
+ Befragung
  + KVH
  + LSH
  + NSH
  + RHB
  + RHD

#### dashboard

Wird komplett über Javascript bedient

#### landingPage

Ist ein HTML Selbstläufer - kaum Code - statische Seite

#### Befragung 

Ist 
1. ein Ordner für die Befragungen
2. eine Seite welche als Startseite für die Befragungen funktioniert
3. werden Hauptsächlich in PHP geschrieben, da es am besten mit Seitenreload - Webseiten harmoniert

##### Ablauf an einem Beispiel der KVH - Befragung

1. Seitenaufruf über index.php mit REQUEST_URI "/KVH" führt zur Befragung.php
2. Seitenaufruf über index.php mit REQUEST_URI "/KVH/start" führt zur Befragung/KVH.php
3. Seitenaufruf über index.php mit REQUEST_URI "/KVH/finish" führt zur Finish.php
