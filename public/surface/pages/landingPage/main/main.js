/* Dies ist ein Modul, das von Partarum dynamisch geladen wird.
   Es exportiert standardmäßig (default) das Surface-Objekt.
*/

export default {
    // 1. Einfache Elemente
    h2_title: {
        _attributes: { style: "border-bottom: 2px solid #007bff; padding-bottom: 0.5rem;" },
        text: "Willkommen im Partarum 2.0"
    },
    
    p_info: "Wenn du diesen Text siehst, hat die Klasse 'Station' dieses File erfolgreich importiert und 'Content' hat es gerendert.",

    // 2. Test: Verschachtelung & Attribute
    div_statusBox: {
        _attributes: { style: "background: #e8f5e9; padding: 15px; margin: 20px 0; border-radius: 4px;" },
        h3: "Status Report",
        ul: [
            { li: "Station Loader: ✅ OK" },
            { li: "Content Renderer: ✅ OK" },
            { li: "Attributes (_attributes): ✅ OK" },
            { li: { span: { _attributes: { class: "success" }, text: "Verschachtelung: ✅ OK" } } }
        ]
    },

    // 3. Test: Interaktivität (Events)
    div_interactive: {
        h3: "Interaktivität",
        button_test: {
            _attributes: { 
                class: "btn",
                addDOMEvent: {
                    type: "click",
                    doThat: () => {
                        alert("🎉 Klick Event erfolgreich! Die Event-Logik in Content funktioniert.");
                    }
                }
            },
            text: "Klick mich für Event-Test"
        }
    },

    // 4. Test: Templates (Das schwierigste Feature)
    div_templates: {
        _attributes: { style: "margin-top: 2rem;" },
        h3: "Template Rendering (aus Cache)",
        
        // Hier referenzieren wir den Key 'userList', den wir in main_test.js in den Cache gelegt haben
        userList: {
            _type: "HTMLCollection", // Wir sagen: Es ist eine Liste
            
            // Das Blueprint für EINE Karte:
            div_card: {
                _attributes: { class: "card" },
                // Name (wird aus Daten gefüllt)
                strong_name: {
                    // Placeholder Struktur, Content füllt 'text' Attribut aus Daten
                    name: {} 
                },
                // Rolle
                span_role: {
                    _attributes: { style: "color: #666; font-size: 0.9em;" },
                    role: {}
                }
            }
        }
    }
};