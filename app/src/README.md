# `app/src/` – PartarumSurface-Integration (`Befragung`)

## Aufgabe

Der Ordner `app/src/Befragung/` bildet die Brücke zwischen dem **PartarumSurface**-Stage-System des Frameworks (`Partarum\PartarumSurface\Stages\*StageInterface`) und der Befragungs-Anwendung in `app/`.

---

## Struktur

```text
app/src/Befragung/
├── Surface.php                  # Haupt-Enum für die Stages (InitialStage .. FinalStage) & Datumsformatierung
├── SurfaceApp.php               # Verbindet Partarum-Instanz, DB-Connection (Medoo) und aktiven Stage-Case
├── SurfaceAPI.php               # Platzhalter für zukünftige Surface-API-Hooks
├── SurfaceInitialStage.php      # Implementiert InitialStageInterface    (/KVH .. /RHD)
├── SurfaceSetupStage.php        # Implementiert SetupStageInterface      (/KVH .. /RHD)
├── SurfaceTransitionStage.php   # Implementiert TransitionStageInterface (/KVH-Befragung .. /RHD-Befragung)
├── SurfaceExecutionStage.php    # Implementiert ExecutionStageInterface  (/KVH-Befragung .. /RHD-Befragung)
└── SurfaceFinalStage.php        # Implementiert FinalStageInterface      (/KVH-Final .. /RHD-Final)
```

---

## Funktionsweise

1. Beim Aufruf einer Befragungs-Route (z. B. `/KVH`, `/KVH-Befragung` oder `/KVH-Final`) instanziiert `SurveyStageController` ein `SurfaceApp`-Objekt mit dem jeweiligen `Surface`-Enum-Case (`Surface::InitialStage`, `Surface::TransitionStage`, `Surface::FinalStage`).
2. `Surface::getCase()` gleicht `$_SERVER["REDIRECT_URL"]` bzw. `$_SERVER["REQUEST_URI"]` gegen die Backed-Enum-Werte des zugehörigen Stage-Enums ab und liefert den ermittelten Case (z. B. `SurfaceInitialStage::KVH`).
3. `SurfaceApp` öffnet über `$this->partarum->createDBConnection()` die Medoo-Datenbankverbindung und übergibt sie an die Models in `app/models/Befragung/`.
