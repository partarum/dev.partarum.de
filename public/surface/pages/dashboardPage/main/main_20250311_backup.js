import {
    PartarumSurfaceCollector as PSC
} from "../../../../../Partarum/PartarumJS/ClientSide/PartarumSurfaceCollector.js";


/**
 *
 * @type {{surface: (PartarumSurfaceTopicStore|Error), requests: (PartarumSurfaceTopicStore|Error), dashboard: (PartarumSurfaceTopicStore|Error), events: (PartarumSurfaceTopicStore|Error)}}
 */
const psc = {
    dashboard: await PSC.init("Dashboard"),
    surface: await PSC.init("Surface"),
    requests: await PSC.init("Requests"),
    events: await PSC.init("Events"),
    dialogues: await PSC.init("Dialogues")
};

let surfaceHelper = await psc.surface.getTheme("Helper");

await surfaceHelper.setData("showMonth", [
    {
        _attributes: {
            value: "januar",
            text: "Januar"
        }
    },
    {
        _attributes: {
            value: "februar",
            text: "Februar"
        }
    },
    {
        _attributes: {
            value: "märz",
            text: "März"
        }
    },
    {
        _attributes: {
            value: "april",
            text: "April"
        }
    },
    {
        _attributes: {
            value: "mai",
            text: "Mai"
        }
    },
    {
        _attributes: {
            value: "juni",
            text: "Juni"
        }
    },
    {
        _attributes: {
            value: "juli",
            text: "Juli"
        }
    },
    {
        _attributes: {
            value: "august",
            text: "August"
        }
    },
    {
        _attributes: {
            value: "september",
            text: "September"
        }
    },
    {
        _attributes: {
            value: "oktober",
            text: "Oktober"
        }
    },
    {
        _attributes: {
            value: "november",
            text: "November"
        }
    },
    {
        _attributes: {
            value: "dezember",
            text: "Dezember"
        }
    }
]);


/**
 *
 * @type {PartarumSurfaceThemeStore|Error|null}
 */
let formOptions = await psc.requests.getTheme("formOptions");

let formFetcher = await psc.requests.getTheme("formFetcher");

let basicForm = await formFetcher.getData("basic");

let excelForm = await formFetcher.getData("excel");

//console.dir(basicForm); // anonymous()


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

// Handler
const surveyHandler = await psc.events.setTheme("surveyHandler");

await surveyHandler.setData("fetchSurveyReaction", (ev, data, outputID, menuID = null, setCallback) => {

    document.getElementById(outputID).classList.replace("prt-sb-inactive", "prt-sb-active");

    (menuID !== null) && document.getElementById(menuID).classList.replace("menu-close", "menu-open");

    document.getElementById("displayOutput").setAttribute("style", "display: grid;");

    document.getElementById("displayInput").setAttribute("style", "display: none;");

    setCallback(data);
});

// Events

// psc.dashboard.events
await surveyHandler.setData("startSurvey_click", {
    type: "click",
    name: "startSurvey",
    topic: "dashboard",
    theme: "Survey",
    targetID: "startSurveyButton",
    doThat: async (ev) => {

        const fetchStartSurvey = await basicForm("formStartSurvey", "checkSurvey");

        document.getElementById("form_oBox_startSurvey").reset();

        (await surveyHandler.getData("fetchSurveyReaction"))(ev, fetchStartSurvey, "form_oBox_startSurvey", "menuStartSurvey", async (json) => {

            let tokenOutput = document.getElementById("set_o_startSurvey");

            const startAllObject = json[0]?.all;

            if(startAllObject !== undefined) {

                const statusRed = [];

                const statusGreen = [];

                const statusOrange = [];

                for(let surveyTopic in startAllObject) {

                    let surveyInputElement = document.getElementById("startSurvey_" + surveyTopic);

                    let surveyStatusElement = document.getElementById("startSurvey_status_" + surveyTopic);

                    let surveyInfo = startAllObject[surveyTopic][0];

                    let {survey_diff, survey_has_been_set, survey_has_been_stopped, survey_max_diff, survey_start, survey_status, survey_stop} = surveyInfo;

                    if(survey_status === 1){

                        surveyInputElement.setAttribute("value", survey_start);

                        surveyInputElement.readOnly = true;

                        surveyInputElement.parentElement.classList.add("status-green");

                        statusGreen.push("startSurvey_" + surveyTopic);

                        let getHumanDate = await surfaceHelper.getData("date-humanized");

                        let humanDate = await getHumanDate(survey_stop);

                        surveyStatusElement.innerText = "aktiv bis einschließlich " + humanDate;
                    } else {

                        if(survey_diff < -survey_max_diff) {

                            surveyInputElement.setAttribute("value", survey_start);

                            surveyInputElement.readOnly = true;

                            surveyInputElement.parentElement.classList.add("status-red");

                            statusRed.push("startSurvey_" + surveyTopic);

                            let getHumanDate = await surfaceHelper.getData("date-humanized");

                            let humanDate = await getHumanDate(survey_stop);

                            surveyStatusElement.innerText = "Seit " + humanDate + " überfällig. Bitte erst beenden!";
                        } else {

                            if(survey_diff > 0) {

                                surveyInputElement.setAttribute("value", survey_start);

                                surveyInputElement.parentElement.classList.add("status-orange");

                                statusOrange.push("startSurvey_" + surveyTopic);

                                surveyStatusElement.innerText = "Geplant - kann abgeändert werden.";
                            } else {

                                surveyInputElement.removeAttribute("value");

                                surveyInputElement.readOnly = false;

                                surveyInputElement.parentElement.classList.remove("status-orange");

                                surveyInputElement.parentElement.classList.remove("status-red")

                                surveyInputElement.parentElement.classList.remove("status-green")

                                surveyStatusElement.innerText = "Nicht geplant - Auswahl möglich.";
                            }
                        }
                    }
                }

                const sessionColorsStart = {
                    red: statusRed,
                    green: statusGreen,
                    orange: statusOrange
                };

                const startSurveyElementAll = document.getElementById("startSurvey_ALL");

                if(sessionColorsStart.green.length > 0){

                    startSurveyElementAll.readOnly = true;
                } else {

                    startSurveyElementAll.readOnly = false;
                }

                window.sessionStorage.setItem("startSurveyColors", JSON.stringify(sessionColorsStart));

            } else {

                if (json[0]?.status === "Your beer is stale") {

                    await (await (await psc.dialogues.getTheme("Global")).getData("dialogSessionIsOver"))();
                }
            }
        });
    }
});

await surveyHandler.setData("setStartSurvey_click", {
    type: "click",
    name: "setStartSurvey",
    topic: "dashboard",
    theme: "Survey",
    targetID: "setStartSurveyButton",
    doThat: async (ev) => {

        const fetchStartSurvey = await basicForm("form_oBox_startSurvey", "startSurvey");

        const colorNames = {
          red: "status-red",
          green: "status-green",
          orange: "status-orange"
        };

        let storageValue = JSON.parse(window.sessionStorage.getItem("startSurveyColors"));

        for(const colorKey in storageValue){

            for(const value of storageValue[colorKey]) {

                const colorElement = document.getElementById(value);

                colorElement.parentElement.classList.remove(colorNames[colorKey]);
            }
        }

        document.getElementById("closeOutputBox_startSurvey").click();
        document.getElementById("startSurveyButton").click();
    }
});

await surveyHandler.setData("stopSurvey_click", {
    type: "click",
    name: "stopSurvey",
    topic: "dashboard",
    theme: "Survey",
    targetID: "stopSurveyButton",
    doThat: async (ev) => {

        const fetchStopSurvey = await basicForm("formStopSurvey", "checkSurvey");

        document.getElementById("form_oBox_stopSurvey").reset();

        (await surveyHandler.getData("fetchSurveyReaction"))(ev, fetchStopSurvey, "form_oBox_stopSurvey", "menuStopSurvey", async (json) => {

            let tokenOutput = document.getElementById("set_o_stopSurvey");

            const stopAllObject = json[0]?.all;

            if(stopAllObject !== undefined) {

                const statusRed = [];

                const statusGreen = [];

                const statusOrange = [];

                for(let surveyTopic in stopAllObject) {

                    let surveyInputElement = document.getElementById("stopSurvey_" + surveyTopic);

                    let surveyStatusElement = document.getElementById("stopSurvey_status_" + surveyTopic);

                    let surveyInfo = stopAllObject[surveyTopic][0];

                    let {survey_diff, survey_has_been_set, survey_has_been_stopped, survey_max_diff, survey_start, survey_status, survey_stop} = surveyInfo;

                    if(survey_status === 1){

                        surveyInputElement.parentElement.classList.add("status-green");

                        statusGreen.push("stopSurvey_" + surveyTopic);

                        let getHumanDate = await surfaceHelper.getData("date-humanized");

                        let humanDate = await getHumanDate(survey_stop);

                        surveyStatusElement.innerText = "aktiv bis einschließlich " + humanDate;
                    } else {

                        if(survey_diff < -survey_max_diff) {

                            surveyInputElement.parentElement.classList.add("status-red");

                            statusRed.push("stopSurvey_" + surveyTopic);

                            let getHumanDate = await surfaceHelper.getData("date-humanized");

                            let humanDate = await getHumanDate(survey_stop);

                            surveyStatusElement.innerText = "überfällig seit dem " + humanDate;
                        } else {

                            if(survey_diff > 0) {

                                surveyInputElement.parentElement.classList.add("status-orange");

                                statusOrange.push("stopSurvey_" + surveyTopic);

                                let getHumanDate = await surfaceHelper.getData("date-humanized");

                                let humanDate = await getHumanDate(survey_start);

                                surveyStatusElement.innerText = "Geplant zum " + humanDate + " - kann abgeändert oder beendet werden.";
                            } else {

                                surveyStatusElement.innerText = "Aktuell ist keine Erhebung aktiv.";
                            }
                        }
                    }
                }

                const sessionColorsStop = {
                    red: statusRed,
                    green: statusGreen,
                    orange: statusOrange
                };

                window.sessionStorage.setItem("stopSurveyColors", JSON.stringify(sessionColorsStop));

            } else {

                if (json[0]?.status === "Your beer is stale") {

                    await (await (await psc.dialogues.getTheme("Global")).getData("dialogSessionIsOver"))();
                }
            }

        });

    }
});

await surveyHandler.setData("setStopSurvey_click", {
    type: "click",
    name: "setStopSurvey",
    topic: "dashboard",
    theme: "Survey",
    targetID: "setStopSurveyButton",
    doThat: async (ev) => {

        const fetchStopSurvey = await basicForm("form_oBox_stopSurvey", "stopSurvey");

        const {stopped, status, stoppedCount, deletedCount} = fetchStopSurvey[0];

        let text = "Es wurden gerade folgende: \n";

        for(const [topicKey, topicCount] of Object.entries(stoppedCount)){

            text += "\n " + topicKey + ": " + topicCount + " Datensätze gelöscht \n"
        }

        alert(text);

        const colorNames = {
            red: "status-red",
            green: "status-green",
            orange: "status-orange"
        };

        let storageValue = JSON.parse(window.sessionStorage.getItem("stopSurveyColors"));

        for(const colorKey in storageValue){

            for(const value of storageValue[colorKey]) {

                const colorElement = document.getElementById(value);

                colorElement.checked = false;

                colorElement.parentElement.classList.remove(colorNames[colorKey]);
            }
        }

        document.getElementById("closeOutputBox_stopSurvey").click();
        document.getElementById("stopSurveyButton").click();
    }
});


await surveyHandler.setData("getExcelData_click", {
    type: "click",
    name: "getExcelData",
    topic: "dashboard",
    theme: "Survey",
    targetID: "getExcelDataButton",
    doThat: async (ev) => {

        const fetchGetExcelData = await basicForm("formGetExcelData", "getExcelData");

        (await surveyHandler.getData("fetchSurveyReaction"))(ev, fetchGetExcelData, "form_oBox_getExcelFile", "menuGetExcel", async (json) => {

            let tokenOutput = document.getElementById("set_o_getExcelData");

            const excelArray = json[0]?.survey_count;

            if (excelArray !== undefined) {

                for (let surveyKey in excelArray) {

                    let span = document.getElementById("getExcelFileCounter_" + surveyKey);

                    if(excelArray[surveyKey] === 0){

                        span.parentElement.style.display = "none";

                    } else {

                        span.parentElement.style.display = "grid";

                        (span.innerText === "") && span.appendChild(document.createTextNode(excelArray[surveyKey] + " Stück"));
                    }
                }
            } else {

                if (json[0]?.status === "Your beer is stale") {

                    await (await (await psc.dialogues.getTheme("Global")).getData("dialogSessionIsOver"))();
                }
            }
        });
    }
});

await surveyHandler.setData("getExcelFileNewStyle_click", {
    type: "click",
    name: "getExcelFile_newStyle",
    topic: "dashboard",
    theme: "Survey",
    targetID: "getExcelFileButton_newStyle",
    doThat: async (ev) => {

        const fetchGetExcelFile = await excelForm("form_oBox_getExcelFile", "getExcelFile", "new");

        console.dir("Download ready");
    }
});

// Surface

const getInputDate = await surfaceHelper.getData("date-input");

let inputDate = await getInputDate();

await main.setData("mainCode", {
    article_input: {
        _attributes: {
            id: "displayInput",
            class: "prt-c1 prt-form prt-sc-form"
        },
        form_startSurvey: {
            _attributes: {
                id: "formStartSurvey",
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
                    h2: "Start / Status Erhebung"
                },
                section: {
                    _attributes: {
                        class: "prt-b2"
                    },
                    button_get: {
                        _attributes: {
                            class: "woodenButton-large",
                            id: "startSurveyButton",
                            type: "button",
                            text: "Weiter",
                            form: "formStartSurvey",
                            addEvent: await surveyHandler.getData("startSurvey_click")
                        }
                    }
                },
                footer: {
                    _attributes: {
                        class: "prt-b3"
                    }
                }
            }
        },
        form_stopSurvey: {
            _attributes: {
                id: "formStopSurvey",
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
                    h2: "Beende Erhebung"
                },
                section: {
                    _attributes: {
                        class: "prt-b2"
                    },
                    button_get: {
                        _attributes: {
                            class: "woodenButton-large",
                            id: "stopSurveyButton",
                            type: "button",
                            text: "Weiter",
                            form: "formStopSurvey",
                            addEvent: await surveyHandler.getData("stopSurvey_click")
                        }
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
                id: "formGetExcelData",
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
                    },
                    button_get: {
                        _attributes: {
                            class: "woodenButton-large",
                            id: "getExcelDataButton",
                            type: "button",
                            text: "Excel auswerten",
                            form: "formGetExcelData",
                            addEvent: await surveyHandler.getData("getExcelData_click")
                        }
                    }
                },
                footer: {
                    _attributes: {
                        class: "prt-b3"
                    }
                }
            }
        },
        form_otherSettings: {
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
                    h2: "Sonstiges"
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
        form_startSurvey: {
            _attributes: {
                id: "form_oBox_startSurvey",
                class: "prt-sc1 prt-sb-inactive"
            },
            fieldset: {
                _attributes: {
                    class: "prt-showBox"
                },
                section: {
                    _attributes: {
                        class: "prt-sb2",
                        id: "set_o_startSurvey"
                    },
                    h3: "Bitte wähle unter folgenden Erhebungen:",
                    section: {
                        _attributes: {
                            id: "set_o_startSurvey",
                            class: "choose-survey-box single-box-center-medium-inside"
                        },
                        ul_header: {
                            li: {
                                _attributes: {
                                    class: "pg-static pgc-r16"
                                },
                                span: [
                                    {
                                        _attributes: {
                                            class: "from-pgc4 to-pgc5",
                                            text: "Erhebung"
                                        }
                                    },
                                    {
                                        _attributes: {
                                            class: "from-pgc6 to-pgc7",
                                            text: "Startdatum"
                                        }
                                    },
                                    {
                                        _attributes: {
                                            class: "pgc2",
                                            text: "Anzeigemonat"
                                        }
                                    },
                                    {
                                        _attributes: {
                                            class: "pgc5",
                                            text: "Status"
                                        }
                                    }
                                ]
                            }
                        },
                        ul_all: {
                            li: {
                                _attributes: {
                                    class: "pg-static pgc-r16"
                                },
                                label: {
                                    _attributes: {
                                        class: "from-pgc4 to-pgc5",
                                        for: "startSurvey_ALL",
                                        text: "Alle"
                                    }
                                },
                                input_start: {
                                    _attributes: {
                                        class: "from-pgc6 to-pgc7",
                                        type: "date",
                                        id: "startSurvey_ALL",
                                        name: "all",
                                        min: inputDate
                                        /*
                                        addEvent: mainTokenEvents.getData("setAllToken")

                                         */
                                    }
                                },
                                select_display_month: {
                                    _attributes: {
                                        class: "pgc2",
                                        name: "showMonth_all"
                                    },
                                    option: await surfaceHelper.getData("showMonth")
                                },
                                span: {
                                    _attributes: {
                                        class: "pgc5"
                                    }
                                }
                            }
                        },
                        ul_select: {
                            li: [
                                {
                                    _attributes: {
                                        class: "pg-static pgc-r16"
                                    },
                                    label: {
                                        _attributes: {
                                            class: "from-pgc4 to-pgc5",
                                            for: "startSurvey_KVH",
                                            text: "KVH"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            class: "from-pgc6 to-pgc7",
                                            type: "date",
                                            id: "startSurvey_KVH",
                                            name: "kvh",
                                            min: inputDate
                                        }
                                    },
                                    select_display_month: {
                                        _attributes: {
                                            class: "pgc2",
                                            name: "showMonth_kvh"
                                        },
                                        option: await surfaceHelper.getData("showMonth")
                                    },
                                    span: {
                                        _attributes: {
                                            class: "pgc5",
                                            id: "startSurvey_status_KVH"
                                        }
                                    }
                                },
                                {
                                    _attributes: {
                                        class: "pg-static pgc-r16"
                                    },
                                    label: {
                                        _attributes: {
                                            class: "from-pgc4 to-pgc5",
                                            for: "startSurvey_LSH",
                                            text: "LSH"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            class: "from-pgc6 to-pgc7",
                                            type: "date",
                                            id: "startSurvey_LSH",
                                            name: "lsh",
                                            min: inputDate
                                        }
                                    },
                                    select_display_month: {
                                        _attributes: {
                                            class: "pgc2",
                                            name: "showMonth_lsh"
                                        },
                                        option: await surfaceHelper.getData("showMonth")
                                    },
                                    span: {
                                        _attributes: {
                                            class: "pgc5",
                                            id: "startSurvey_status_LSH"
                                        }
                                    }
                                },
                                {
                                    _attributes: {
                                        class: "pg-static pgc-r16"
                                    },
                                    label: {
                                        _attributes: {
                                            class: "from-pgc4 to-pgc5",
                                            for: "startSurvey_NSH",
                                            text: "NSH"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            class: "from-pgc6 to-pgc7",
                                            type: "date",
                                            id: "startSurvey_NSH",
                                            name: "nsh",
                                            min: inputDate
                                        }
                                    },
                                    select_display_month: {
                                        _attributes: {
                                            class: "pgc2",
                                            name: "showMonth_nsh"
                                        },
                                        option: await surfaceHelper.getData("showMonth")
                                    },
                                    span: {
                                        _attributes: {
                                            class: "pgc5",
                                            id: "startSurvey_status_NSH"
                                        }
                                    }
                                },
                                {
                                    _attributes: {
                                        class: "pg-static pgc-r16"
                                    },
                                    label: {
                                        _attributes: {
                                            class: "from-pgc4 to-pgc5",
                                            for: "startSurvey_RHB",
                                            text: "RHB"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            class: "from-pgc6 to-pgc7",
                                            type: "date",
                                            id: "startSurvey_RHB",
                                            name: "rhb",
                                            min: inputDate
                                        }
                                    },
                                    select_display_month: {
                                        _attributes: {
                                            class: "pgc2",
                                            name: "showMonth_rhb"
                                        },
                                        option: await surfaceHelper.getData("showMonth")
                                    },
                                    span: {
                                        _attributes: {
                                            class: "pgc5",
                                            id: "startSurvey_status_RHB"
                                        }
                                    }
                                },
                                {
                                    _attributes: {
                                        class: "pg-static pgc-r16"
                                    },
                                    label: {
                                        _attributes: {
                                            class: "from-pgc4 to-pgc5",
                                            for: "startSurvey_RHD",
                                            text: "RHD"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            class: "from-pgc6 to-pgc7",
                                            type: "date",
                                            id: "startSurvey_RHD",
                                            name: "rhd",
                                            min: inputDate
                                        }
                                    },
                                    select_display_month: {
                                        _attributes: {
                                            class: "pgc2",
                                            name: "showMonth_rhd"
                                        },
                                        option: await surfaceHelper.getData("showMonth")
                                    },
                                    span: {
                                        _attributes: {
                                            class: "pgc5",
                                            id: "startSurvey_status_RHD"
                                        }
                                    }
                                }
                            ]
                        }
                    }
                },
                footer: {
                    _attributes: {
                        class: "prt-sb3"
                    },
                    button_startSurvey: {
                        _attributes: {
                            class: "woodenButton-small",
                            id: "setStartSurveyButton",
                            type: "button",
                            text: "Setze Start der Erhebung",
                            form: "form_oBox_startSurvey",
                            addEvent: await surveyHandler.getData("setStartSurvey_click")
                        }
                    }
                }
            }
        },
        form_stopSurvey: {
            _attributes: {
                id: "form_oBox_stopSurvey",
                class: "prt-sc2 prt-sb-inactive"
            },
            fieldset: {
                _attributes: {
                    class: "prt-showBox"
                },
                section: {
                    _attributes: {
                        class: "prt-sb2"
                    },
                    h3: "Bitte wähle unter folgenden Erhebungen:",
                    section: {
                        _attributes: {
                            id: "set_o_stopSurvey",
                            class: "choose-survey-box span-right"
                        },
                        ul_all: {
                            li: {
                                label: {
                                    _attributes: {
                                        for: "stopSurvey_ALL",
                                        text: "Alle"
                                    }
                                },
                                input: {
                                    _attributes: {
                                        type: "checkbox",
                                        id: "stopSurvey_ALL",
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
                                            for: "stopSurvey_KVH",
                                            text: "KVH"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            type: "checkbox",
                                            id: "stopSurvey_KVH",
                                            name: "kvh"
                                        }
                                    },
                                    span: {
                                        _attributes: {
                                            id: "stopSurvey_status_KVH"
                                        }
                                    }
                                },
                                {
                                    label: {
                                        _attributes: {
                                            for: "stopSurvey_LSH",
                                            text: "LSH"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            type: "checkbox",
                                            id: "stopSurvey_LSH",
                                            name: "lsh"
                                        }
                                    },
                                    span: {
                                        _attributes: {
                                            id: "stopSurvey_status_LSH"
                                        }
                                    }
                                },
                                {
                                    label: {
                                        _attributes: {
                                            for: "stopSurvey_NSH",
                                            text: "NSH"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            type: "checkbox",
                                            id: "stopSurvey_NSH",
                                            name: "nsh"
                                        }
                                    },
                                    span: {
                                        _attributes: {
                                            id: "stopSurvey_status_NSH"
                                        }
                                    }
                                },
                                {
                                    label: {
                                        _attributes: {
                                            for: "stopSurvey_RHB",
                                            text: "RHB"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            type: "checkbox",
                                            id: "stopSurvey_RHB",
                                            name: "rhb"
                                        }
                                    },
                                    span: {
                                        _attributes: {
                                            id: "stopSurvey_status_RHB"
                                        }
                                    }
                                },
                                {
                                    label: {
                                        _attributes: {
                                            for: "stopSurvey_RHD",
                                            text: "RHD"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            type: "checkbox",
                                            id: "stopSurvey_RHD",
                                            name: "rhd"
                                        }
                                    },
                                    span: {
                                        _attributes: {
                                            id: "stopSurvey_status_RHD"
                                        }
                                    }
                                }
                            ]
                        }
                    }
                },
                footer: {
                    _attributes: {
                        class: "prt-sb3"
                    },
                    button_stopSurvey: {
                        _attributes: {
                            class: "woodenButton-small",
                            id: "setStopSurveyButton",
                            type: "button",
                            text: "Erhebung beenden",
                            form: "form_oBox_stopSurvey",
                            addEvent: await surveyHandler.getData("setStopSurvey_click")
                        }
                    }
                }
            }
        },
        form_getExcel: {
            _attributes: {
                id: "form_oBox_getExcelFile",//"oBox_getExcelData",
                class: "prt-sc3 prt-sb-inactive"
            },
            fieldset: {
                _attributes: {
                    class: "prt-showBox"
                },
                header: {
                    _attributes: {
                        class: "prt-sb1"
                    },
                    h3: "Bitte wähle unter folgenden Erhebungen:"
                },
                section: {
                    _attributes: {
                        class: "prt-sb2"
                    },
                    section: {
                        _attributes: {
                            id: "set_o_getExcelData",
                            class: "choose-survey-box"
                        },
                        ul_all: {
                            li: {
                                label: {
                                    _attributes: {
                                        for: "getExcelFile_ALL",
                                        text: "Alle"
                                    }
                                },
                                span: {
                                    span: {
                                        _attributes: {
                                            id: "getExcelFileCounter_ALL"
                                        }
                                    },
                                },
                                input: {
                                    _attributes: {
                                        type: "checkbox",
                                        id: "getExcelFile_ALL",
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
                                            for: "getExcelFile_KVH",
                                            text: "KVH"
                                        }
                                    },
                                    span: {
                                        _attributes: {
                                            id: "getExcelFileCounter_KVH"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            type: "checkbox",
                                            id: "getExcelFile_KVH",
                                            name: "kvh"
                                        }
                                    },
                                    span_status: {
                                        _attributes: {
                                            id: "getExcelFileStatus_KVH"
                                        }
                                    }
                                },
                                {
                                    label: {
                                        _attributes: {
                                            for: "getExcelFile_LSH",
                                            text: "LSH"
                                        }
                                    },
                                    span: {
                                        _attributes: {
                                            id: "getExcelFileCounter_LSH"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            type: "checkbox",
                                            id: "getExcelFile_LSH",
                                            name: "lsh"
                                        }
                                    },
                                    span_status: {
                                        _attributes: {
                                            id: "getExcelFileStatus_LSH"
                                        }
                                    }
                                },
                                {
                                    label: {
                                        _attributes: {
                                            for: "getExcelFile_NSH",
                                            text: "NSH"
                                        }
                                    },
                                    span: {
                                        _attributes: {
                                            id: "getExcelFileCounter_NSH"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            type: "checkbox",
                                            id: "getExcelFile_NSH",
                                            name: "nsh"
                                        }
                                    },
                                    span_status: {
                                        _attributes: {
                                            id: "getExcelFileStatus_NSH"
                                        }
                                    }
                                },
                                {
                                    label: {
                                        _attributes: {
                                            for: "getExcelFile_RHB",
                                            text: "RHB"
                                        }
                                    },
                                    span: {
                                        _attributes: {
                                            id: "getExcelFileCounter_RHB"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            type: "checkbox",
                                            id: "getExcelFile_RHB",
                                            name: "rhb"
                                        }
                                    },
                                    span_status: {
                                        _attributes: {
                                            id: "getExcelFileStatus_RHB"
                                        }
                                    }
                                },
                                {
                                    label: {
                                        _attributes: {
                                            for: "getExcelFile_RHD",
                                            text: "RHD"
                                        }
                                    },
                                    span: {
                                        _attributes: {
                                            id: "getExcelFileCounter_RHD"
                                        }
                                    },
                                    input: {
                                        _attributes: {
                                            type: "checkbox",
                                            id: "getExcelFile_RHD",
                                            name: "rhd"
                                        }
                                    },
                                    span_status: {
                                        _attributes: {
                                            id: "getExcelFileStatus_RHD"
                                        }
                                    }
                                }
                            ]
                        }
                    }
                },
                footer: {
                    _attributes: {
                        class: "prt-sb3"
                    },
                    button_get_newStyle: {
                        _attributes: {
                            class: "woodenButton-small",
                            id: "getExcelFileButton_newStyle",
                            type: "button",
                            text: "Download neue Formatierung",
                            form: "form_oBox_getExcelFile",
                            addEvent: await surveyHandler.getData("getExcelFileNewStyle_click")
                        }
                    }
                }
            }
        },
        form_otherSettings: {
            _attributes: {
                id: "formOtherSettings",
                class: "prt-sc4 prt-sb-inactive"
            },
            fieldset: {
                _attributes: {
                    class: "prt-showBox"
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
            output: {}
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
            form: {}
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