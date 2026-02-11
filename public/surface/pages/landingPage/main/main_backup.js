 /*
 *           Copyright 2020 © Alexander Bombis. All rights reserved.
 *           Developed by Alexander Bombis.
 *           Email: email@alexander-bombis.de
 *
 *           The following code was created based on the template for the website http://cordes-software.de.
 *           This may also be used in full by the person or business
 *           representing the domain "cordes-software.de"
 *           and also modified for their use.
 */
//import surface from "/surface/import";

export default {
    // 1. Container für alles
    div_wrapper: {
        _attributes: {
            id: "partarum-test-wrapper",
            style: "font-family: sans-serif; max-width: 800px; margin: 2rem auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"
        },

        // 2. Einfacher Header (Testet handleNode & TextNode)
        h1_title: {
            _attributes: {
                style: "color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px;"
            },
            text: "Partarum Content Test 🚀" // Alternativer Weg für Text
        },

        // 3. Einführungstext
        p_intro: "Dies ist ein Test für die refactored Content-Klasse. Wenn du das siehst, funktioniert das einfache Rendering.",

        // 4. Testbereich: Attribute & Styling
        div_styling: {
            _attributes: {
                style: "background-color: #f0f0f0; padding: 15px; margin: 20px 0; border-radius: 4px;"
            },
            h3: "1. Attribute & Styles",
            p: {
                _attributes: {
                    class: "text-muted", // Setzt voraus, dass CSS vorhanden ist, oder einfach als Attribut-Test
                    "data-test": "success"
                },
                text: "Dieses Element hat eine Hintergrundfarbe, Padding und Data-Attribute."
            }
        },

        // 5. Testbereich: Listen / Arrays (Testet renderList)
        div_lists: {
            h3: "2. Listen (Array Rendering)",
            ul: [
                { li: "Listenpunkt 1 (String)" },
                { li: "Listenpunkt 2 (String)" },
                {
                    li: {
                        b: "Listenpunkt 3 (Objekt mit Fett-Tag)"
                    }
                }
            ]
        },

        // 6. Testbereich: Events (Testet handleAttributes -> addDOMEvent)
        div_events: {
            h3: "3. Interaktivität (Events)",
            button_clickme: {
                _attributes: {
                    style: "background-color: #27ae60; color: white; border: none; padding: 10px 20px; cursor: pointer; font-size: 16px; border-radius: 4px;",
                    addDOMEvent: {
                        type: "click",
                        doThat: () => {
                            alert("Event funktioniert! Partarum lebt.");
                        }
                    }
                },
                text: "Klick mich!"
            }
        },

        // 7. Testbereich: Templates (Testet handleTemplate & Cache-Zugriff)
        div_templates: {
            h3: "4. Template Rendering (Daten aus Cache)",

            /* ACHTUNG: Damit das funktioniert, müssen wir im Test-Setup
               Daten in Cache.TemplateCache.currentValues['mockUserList'] injizieren!
            */
            mockUserList: {
                _type: "HTMLCollection", // Signalisiert, dass es eine Liste ist

                // Definition, wie EIN Item aussehen soll
                // Im Refactoring iterieren wir über die Daten und erstellen Klone dieses Elements
                div_userCard: {
                    _attributes: {
                        style: "border: 1px solid #ddd; padding: 10px; margin-bottom: 5px; display: flex; justify-content: space-between;"
                    },
                    span_name: {
                        // Hier nehmen wir an, dass die Daten im Cache bereits 'text' Attribute haben
                        // Oder wir nutzen simple Strings, je nach Struktur der Testdaten
                    },
                    span_role: {
                        _attributes: {
                            style: "font-weight: bold; color: #888;"
                        }
                    }
                }
            }
        }
    }
}