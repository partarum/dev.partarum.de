# Login - System

## Beschreibung

+ das System muss im Workspace laufen und für den User integrierbar sein
+ das System hat einen eigenen ProxyPath Partarum/PartarumLogin
+ das System ist sozusagen global im DOCUMENT_ROOT nutzbar und individuell - für jeden Zweck | jede App | jede Seite - konfigurierbar 
+ das System muss strict zwischen den Verwendungen unterscheiden - die Verwendungen dürfen sich nicht in die Quere kommen
+ das System managed sämtliche Passwörter für sämtliche Integrationen | Verwendungen

## Software

+ CouchDB - nicht als Proxy - sondern nur intern über localhost:5984/_utils | localhost:5984

## Ablauf

1. Workspace
    1. 
2. User / app | site
   1. User erstellt sich eine z.B. app/loginPage.php und benutzt PartarumLogin um sich auf seinen Seiten zu verifizieren
   2. Wie kann der User es verwenden | einbinden?
      1. 
## Umsetzung