# `app/modules/` – Fachliche Module & Services

## Aufgabe

Der Ordner `app/modules/` gruppiert domänenspezifische Services, Hilfsklassen und Modul-Enums, die Geschäftslogik außerhalb der reinen Datenbank-Models kapseln (z. B. Session-/Cookie-Authentifizierung, Excel-Generierung über `PhpSpreadsheet` oder `PartarumSurface`-Beispiele).

---

## Struktur

```text
app/modules/
├── dashboard/
│   ├── DashboardApiAction.php      # Backed Enum aller 7 Dashboard-API-Aktionen
│   ├── DashboardAuthService.php    # Login-Handshake (beer -> bearer -> arrival), Session & Redirects
│   ├── DashboardExcelService.php   # Erzeugung & Streaming der XLSX-Dateien (Erhebung.xlsx & example.xlsx)
│   ├── DashboardStage.php          # Backed Enum der Dashboard-Seitenrouten (Login, Authenticate, Workspace, Logout)
│   └── README.md                   # Fachliche & technische Dokumentation des Dashboard-Moduls
└── SurfaceExample/                 # Beispiel-Implementierung für PartarumSurface-Stages
```