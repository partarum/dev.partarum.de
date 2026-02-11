// Pfad bitte anpassen, falls Ihre Partarum.js woanders liegt!
import { Partarum, Cache } from "../../../Partarum/PartarumJS/ClientSide/Partarum.js";

console.log("🚀 Starte Test-Konfiguration...");

// 1. Templates vorbereiten (Simuliert geladene Daten)
// Diese Daten werden vom neuen Content-Renderer automatisch gefunden.
const userTemplateData = {
    "userList": [
        { name: { _attributes: { text: "Alexander Bombis" }}, role: { _attributes: { text: "Architect" }} },
        { name: { _attributes: { text: "System Test" }}, role: { _attributes: { text: "Runner" }} },
        { name: { _attributes: { text: "Partarum JS" }}, role: { _attributes: { text: "Framework" }} }
    ]
};

// Wir injecten das direkt in den Cache, um den Template-Download zu überspringen (Unit-Test Style)
// Da Partarum.js jetzt Cache.TemplateCache initialisiert, können wir das nutzen.
// Achtung: Wir machen das NACHDEM Partarum geladen wurde, aber VOR create().
Cache.TemplateCache = { currentValues: userTemplateData };


// 2. Die App Konfiguration
const myAppConfig = {
    theme: "testTheme", // Name des Themes
    type: "single",     // Wir testen den Single-Page Modus

    // Config = Pfad zum Modul, das geladen werden soll
    config: "../../../public/surface/pages/landingPage/main/main.js",

    // Wo soll gerendert werden?
    parent: document.getElementById("content")
};

// 3. Starten
const app = new Partarum(myAppConfig);

app.create(() => {
    console.log("✅ System: Callback erreicht! App sollte sichtbar sein.");
});