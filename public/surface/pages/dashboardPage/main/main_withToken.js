import {
    PartarumSurfaceCollector as PSC
} from "../../../../../Partarum/PartarumJS/ClientSide/PartarumSurfaceCollector.js";


/**
 *
 * @type {{surface: (PartarumSurfaceTopicStore|Error), requests: (PartarumSurfaceTopicStore|Error), dashboard: (PartarumSurfaceTopicStore|Error), events: (PartarumSurfaceTopicStore|Error)}}
 */
const psc = {
    dashboard:  await PSC.init("Dashboard"),
    surface: await PSC.init("Surface"),
    requests: await PSC.init("Requests"),
    events: await PSC.init("Events"),
    dialogues: await PSC.init("Dialogues")
};

/**
 *
 * @type {PartarumSurfaceThemeStore|Error|null}
 */
let formOptions = await psc.requests.getTheme("formOptions");

/**
 *
 * @type {PartarumSurfaceThemeStore|Error|null}
 */
const beerWorker = await window.PartarumWorker.getTheme("BeerWorker");

const backgroundFetcher = await window.PartarumWorker.getTheme("BackgroundFetcher");

/**
 *
 * @type {PartarumSurfaceThemeStore|Error}
 */
const main = await psc.surface.setTheme("main");

const tokenRequests = await psc.requests.setTheme("Token");

await tokenRequests.setData("fetchToken", () => {

    return new Promise(async (resolve, reject) => {

        let formElement = document.getElementById("formGetToken");

        if (formElement instanceof HTMLFormElement) {

            let options = await formOptions.getData("regular");

            // TODO: Man kann zwischen dem Hauptthread und einem Worker keine FormData klonen / senden.

            // TODO: FormData to Object als Helper integrieren!
            let formBody = new FormData(formElement);

            let objectBody = {};

            for(const [key, value] of formBody.entries()){

                objectBody[key] = value;
            }

            options.body = objectBody;

            // TODO: Die URL muss erstmal mitgegeben werden - der Worker kann nur über die IndexDB zwischenspeichern. - Was noch Möglich ist wäre im PKCE  - Stil die URL vom Server zu holen.
            // TODO: IndexDB vs PKCE - Stil
            await (await backgroundFetcher.getData("callHome"))("getToken", options, "form", "json").then((json) => {

                resolve(json);
            }).catch((error) =>{

                reject(error);
            });
        }
    });
});

const getTokenHandler = await psc.events.setTheme("getTokenHandler");

await getTokenHandler.setData("fetchTokenReaction", (ev, json, outputID, setCallback) => {

    document.getElementById(outputID).classList.replace("prt-sb-inactive", "prt-sb-active")

    setCallback(json);
});

// psc.dashboard.events
await getTokenHandler.setData("getToken_click", {
    type: "click",
    name: "getToken",
    topic: "dashboard",
    theme: "Token",
    targetID: "getTokenButton",
    doThat: async (ev) => {

        if(window.confirm(" \n Möchtest du wirklich neue Token generieren? \n \n Diese Aktion kann nicht widerrufen werden! \n ")) {

            (await tokenRequests.getData("fetchToken"))().then(async (json) => {

                (await getTokenHandler.getData("fetchTokenReaction"))(ev, json, "getToken", async (json) => {

                    let tokenOutput = document.getElementById("setTokenOutput");

                    let menu = document.getElementById("menuGetToken");

                    menu.classList.replace("menu-close", "menu-open")

                    console.dir(json);

                    const tokenArray = json[0]?.token;

                    if (tokenArray !== undefined) {

                        for (let tokenKey in tokenArray) {

                            let ul = document.createElement("ul");

                            let liLabel = document.createElement("li");

                            let liInput = document.createElement("li");

                            let label = document.createElement("label");

                            label.appendChild(document.createTextNode(tokenKey.toUpperCase()));

                            label.setAttribute("for", "output_" + tokenKey);

                            liLabel.appendChild(label);

                            let input = document.createElement("input");

                            input.setAttribute("id", "output_" + tokenKey);

                            input.setAttribute("value", tokenArray[tokenKey]);

                            input.setAttribute("type", "text");

                            input.setAttribute("readonly", "readonly");

                            liInput.appendChild(input);

                            let buttonCopy = document.createElement("button");

                            buttonCopy.classList.add("copy-button");

                            buttonCopy.appendChild(document.createTextNode("Kopieren"));

                            buttonCopy.addEventListener("click", (ev) => {

                                navigator.clipboard.writeText(tokenArray[tokenKey]).then(() => {
                                    input.setAttribute("style", "background: teal;");
                                });
                            });

                            liInput.appendChild(buttonCopy);

                            ul.appendChild(liLabel);

                            ul.appendChild(liInput);

                            tokenOutput.appendChild(ul);
                        }
                    } else {

                        if (json[0]?.status === "Your beer is stale") {

                            await (await (await psc.dialogues.getTheme("Global")).getData("dialogSessionIsOver"))();
                        }
                    }
                });
            });
        }
    }
});

await getTokenHandler.setData("setAllToken", {
    type: "check"
});



await main.setData("mainCode", {
    article_input: {
        _attributes: {
            id: "displayInput",
            class: "prt-c1 prt-form prt-sc-form"
        },
        form_setToken: {
            _attributes: {
                id: "formGetToken",
                class: "prt-f1 prt-fieldset"
            },
            fieldset: {
                _attributes: {
                    class: "prt-fs1 prt-box"
                },
                header: {
                    _attributes: {
                        class: "prt-b1"
                    },
                    h2: "Token generieren"
                },
                section: {
                    _attributes: {
                        class: "prt-b2"
                    },
                    h3: "Bitte wähle unter folgenden Optionen:",
                    section: {
                        _attributes: {
                            id: "chooseTokenBox"
                        },
                        ul_all: {
                            li: {
                                label: {
                                    _attributes: {
                                        for: "setToken_ALL",
                                        text: "Alle"
                                    }
                                },
                                input: {
                                    _attributes: {
                                        type: "checkbox",
                                        id: "setToken_ALL",
                                        name: "all"
                                        /*
                                        addEvent: mainTokenEvents.getData("setAllToken")

                                         */
                                    }
                                }
                            }
                        },
                        ul_select: {
                            li: [
                                {
                                    label: {
                                        _attributes: {
                                            for: "setToken_KVH",
                                            text: "KVH"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            type: "checkbox",
                                            id: "setToken_KVH",
                                            name: "kvh"
                                        }
                                    }
                                },
                                {
                                    label: {
                                        _attributes: {
                                            for: "setToken_LSH",
                                            text: "LSH"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            type: "checkbox",
                                            id: "setToken_LSH",
                                            name: "lsh"
                                        }
                                    }
                                },
                                {
                                    label: {
                                        _attributes: {
                                            for: "setToken_NSH",
                                            text: "NSH"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            type: "checkbox",
                                            id: "setToken_NSH",
                                            name: "nsh"
                                        }
                                    }
                                },
                                {
                                    label: {
                                        _attributes: {
                                            for: "setToken_RHB",
                                            text: "RHB"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            type: "checkbox",
                                            id: "setToken_RHB",
                                            name: "rhb"
                                        }
                                    }
                                },
                                {
                                    label: {
                                        _attributes: {
                                            for: "setToken_RHD",
                                            text: "RHD"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            type: "checkbox",
                                            id: "setToken_RHD",
                                            name: "rhd"
                                        }
                                    }
                                }
                            ]
                        }
                    }
                },
                footer: {
                    _attributes: {
                        class: "prt-b3"
                    },
                    button_get: {
                        _attributes: {
                            class: "inline-block",
                            id: "getTokenButton",
                            type: "button",
                            text: "Absenden",
                            form: "formGetToken",
                            addEvent: await getTokenHandler.getData("getToken_click")
                        }
                    }
                }
            }
        },
        form_startBefragung: {
            _attributes: {
                class: "prt-f2 prt-fieldset"
            },
            fieldset: {
                _attributes: {
                    class: "prt-fs1 prt-box"
                },
                header: {
                    _attributes: {
                        class: "prt-b1"
                    },
                    h2: "Starte Befragung"
                },
                section: {
                    _attributes: {
                        class: "prt-b2"
                    }
                },
                footer: {
                    _attributes: {
                        class: "prt-b3"
                    }
                }
            }
        },
        form_getExcel: {
            _attributes: {
                class: "prt-f3 prt-fieldset"
            },
            fieldset: {
                _attributes: {
                    class: "prt-fs1 prt-box"
                },
                header: {
                    _attributes: {
                        class: "prt-b1"
                    },
                    h2: "Generiere Excel - Datei von"
                },
                section: {
                    _attributes: {
                        class: "prt-b2"
                    }
                },
                footer: {
                    _attributes: {
                        class: "prt-b3"
                    }
                }
            }
        },
        form_stopBefragung: {
            _attributes: {
                class: "prt-f4 prt-fieldset"
            },
            fieldset: {
                _attributes: {
                    class: "prt-fs1 prt-box"
                },
                header: {
                    _attributes: {
                        class: "prt-b1"
                    },
                    h2: "Beende Befragung"
                },
                section: {
                    _attributes: {
                        class: "prt-b2"
                    }
                },
                footer: {
                    _attributes: {
                        class: "prt-b3"
                    }
                }
            }
        }
    },
    article_output: {
        _attributes: {
            id: "displayOutput",
            class: "prt-c0 prt-showContainer"
        },
        section_getToken: {
            _attributes: {
                id: "getToken",
                class: "prt-sc1 prt-showBox prt-sb-inactive"
            },
            header: {
                _attributes: {
                    class: "prt-sb1"
                },
                h2: "Ausgabe deiner Token",
                p: "Bitte beachte das deine alten - sofern vorhanden - Token jetzt gelöscht sind."
            },
            section: {
                _attributes: {
                    class: "prt-sb2",
                    id: "setTokenOutput"
                }
            },
            footer: {
                _attributes: {
                    class: "prt-sb3"
                }
            }
        },
        section_startSurvey: {
            _attributes: {
                id: "startSurvey",
                class: "prt-sc2 prt-showBox prt-sb-inactive"
            },
            header: {
                _attributes: {
                    class: "prt-sb1"
                }
            },
            section: {
                _attributes: {
                    class: "prt-sb2"
                },
            },
            footer: {
                _attributes: {
                    class: "prt-sb3"
                }
            }
        },
        section_getExcel: {
            _attributes: {
                id: "getExcel",
                class: "prt-sc3 prt-showBox prt-sb-inactive"
            },
            header: {
                _attributes: {
                    class: "prt-sb1"
                }
            },
            section: {
                _attributes: {
                    class: "prt-sb2"
                },
            },
            footer: {
                _attributes: {
                    class: "prt-sb3"
                },
            }
        },
        section_stopSurvey: {
            _attributes: {
                id: "stopSurvey",
                class: "prt-sc4 prt-showBox prt-sb-inactive"
            },
            header: {
                _attributes: {
                    class: "prt-sb1"
                }
            },
            section: {
                _attributes: {
                    class: "prt-sb2"
                },
            },
            footer: {
                _attributes: {
                    class: "prt-sb3"
                },
            }
        }
    },
    article_dialoguesGlobal: {
        _attributes: {
            id: "dialoguesGlobal",
            class: "prt-0"
        },
        dialog_sessionIsOver: {
            _attributes: {
                id: "dialogSessionIsOver",
            },
            h3: "Deine Sitzung ist nicht mehr gültig. Du wirst in 5 Sekunden automatisch zum Login weitergeleitet.",
            output: {

            }
        }
    },
    article_dialoguesInput: {
        _attributes: {
            id: "dialoguesInput",
            class: "prt-0"
        },
        dialog_getToken: {
            _attributes: {
                id: "dialog_getToken"
            },
            form: {

            }
        }
    },
    article_dialoguesOutput: {
        _attributes: {
            id: "dialoguesOutput",
            class: "prt-0"
        }
    }
});

export default await main.getData("mainCode");