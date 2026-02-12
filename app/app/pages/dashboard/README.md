# Dashboard

## Was muss das Dashboard können?

1. Token für die Clienten erstellen
2. Den Monat für die Befragungen festlegen
3. Die Befragung anstoßen
4. Die Excel - Datei generieren und zum Download anbieten
5. Bereinigung der DB

### 1. Token für die Clienten erstellen

+ Gilt als Eintritt ins Routing zur Befragung
  + wird nach Eintritt im Header als Bearer gesendet
+ Token wird mithilfe der neuen E-Mail-Adresse und der zuständigen Befragung erstellt
  + ggf. JWT
+ Token wird in einem Storage gesammelt
+ sämtliche schon vorhandenen E-Mail-Adressen benötigen einen Token
  + Eine Massenproduktion verfügbar machen
+ API für Token bereitstellen

### 2. Den Monat für die Befragungen festlegen

+ ist verbindend mit Punkt 3

### 3. Die Befragung anstoßen

+ Befragung wird angestoßen mit Eingabe / Bestätigung des Monats, 
auf welchen sie sich beziehen soll
+ Ab den Moment ist die Route offen
  + Die Routen für die Befragungen sind ansonsten nicht erreichbar
  + Der Zeitraum wird durch Franz-Josef definiert und in der DB gespeichert
    + Zeitraum ist abänderbar

### 4. Die Excel - Datei generieren und zum Download anbieten

+ die Excel wird via PHPSpreadsheet aus den JSON Daten, welche in der DB gespeichert sind, erstellt
+ Excel wird zum Download angeboten

### 5. Bereinigung der DB

+ die Daten der jeweiligen Befragung werden gelöscht
+ alle Daten aller Befragungen werden gelöscht