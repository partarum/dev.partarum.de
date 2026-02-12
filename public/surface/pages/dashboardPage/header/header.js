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
    events: await PSC.init("Events")
};

const beerWorker = await window.PartarumWorker.getTheme("BeerWorker");

const backgroundFetcher = await window.PartarumWorker.getTheme("BackgroundFetcher");

const header = await psc.surface.setTheme("header");

const formOptions = await psc.requests.getTheme("formOptions");

const headerTokenEvents = await psc.events.setTheme("headerTokenEvents");

await headerTokenEvents.setData("getTokenButtonDownload", {

    type: "click",
    name: "getTokenButtonDownload",
    topic: "dashboard",
    theme: "Token",
    targetID: "getTokenButtonDownload",
    doThat: async (ev) => {

        await (await backgroundFetcher.getData("callHome"))("getTokenExcel", await formOptions.getData("regular"), null, "blob").then((blob) => {

            const url = window.URL.createObjectURL(new Blob([blob]));
            const a = document.createElement('a');
            a.href = url;
            a.download = 'example.xlsx';
            document.body.appendChild(a);
            a.click();
            a.remove();
        });
    }
});

const generalHeaderEvents = await psc.events.setTheme("general");

await generalHeaderEvents.setData("closeOutputBox", (ev, data, outputID, menuID, setCallback) => {

    document.getElementById(outputID).classList.replace("prt-sb-active", "prt-sb-inactive");

    document.getElementById(menuID).classList.replace("menu-open", "menu-close");

    document.getElementById("displayOutput").setAttribute("style", "display: none;");

    document.getElementById("displayInput").setAttribute("style", "display: grid;");

    setCallback(data);
});

await generalHeaderEvents.setData("closeOutputBox_startSurvey", {

    type: "click",
    name: "closeOutputBoxStartSurvey",
    topic: "dashboard",
    theme: "General",
    targetID: "closeOutputBox_startSurvey",
    doThat: async (ev) => {

        (await generalHeaderEvents.getData("closeOutputBox"))(ev, null, "form_oBox_startSurvey", "menuStartSurvey", async (data) => {

        });
    }
});

await generalHeaderEvents.setData("closeOutputBox_stopSurvey", {

    type: "click",
    name: "closeOutputBoxStopSurvey",
    topic: "dashboard",
    theme: "General",
    targetID: "closeOutputBox_stopSurvey",
    doThat: async (ev) => {

        (await generalHeaderEvents.getData("closeOutputBox"))(ev, null, "form_oBox_stopSurvey", "menuStopSurvey", async (data) => {

        });
    }
});

await generalHeaderEvents.setData("closeOutputBox_getExcel", {

    type: "click",
    name: "closeOutputBoxGetExcel",
    topic: "dashboard",
    theme: "General",
    targetID: "closeOutputBox_getExcel",
    doThat: async (ev) => {

        (await generalHeaderEvents.getData("closeOutputBox"))(ev, null, "form_oBox_getExcelFile", "menuGetExcel", async (data) => {

        });
    }
});

await generalHeaderEvents.setData("logOut", {

    type: "click",
    name: "logOut",
    topic: "dashboard",
    theme: "General",
    targetID: "closeButton_logOut",
    doThat: async (ev) => {

        window.sessionStorage.clear();

        window.location.replace(window.location.origin + "/logoutPage");
    }
});


await header.setData("headerCode", {
    menu_general: {
        _attributes: {
            id: "menuGeneral",
            class: "menu-open"
        },
        li: [
            {
                button_logOut: {
                    _attributes: {
                        type: "button",
                        text: "Abmelden",
                        id: "closeButton_logOut",
                        addEvent: await generalHeaderEvents.getData("logOut")
                    }
                }
            }
        ]
    },
    menu_getToken: {
        _attributes: {
            id: "menuGetToken",
            class: "menu-close"
        },
        li: [
            {
                button_close: {
                    _attributes: {
                        type: "button",
                        text: "Fenster schließen",
                        id: "closeButtonGetToken"
                    }
                },
                button_download: {
                    _attributes: {
                        type: "button",
                        text: "Download Excel",
                        id: "downloadButtonGetToken",
                        addEvent: await headerTokenEvents.getData("getTokenButtonDownload")
                    }
                }
            }
        ]
    },
    menu_startSurvey: {
        _attributes: {
            id: "menuStartSurvey",
            class: "menu-close"
        },
        li: [
            {
                button_close: {
                    _attributes: {
                        text: "Fenster schließen",
                        id: "closeOutputBox_startSurvey",
                        addEvent: await generalHeaderEvents.getData("closeOutputBox_startSurvey")
                    }
                }
            }
        ]
    },
    menu_stopSurvey: {
        _attributes: {
            id: "menuStopSurvey",
            class: "menu-close"
        },
        li: [
            {
                button_close: {
                    _attributes: {
                        text: "Fenster schließen",
                        id: "closeOutputBox_stopSurvey",
                        addEvent: await generalHeaderEvents.getData("closeOutputBox_stopSurvey")
                    }
                }
            }
        ]
    },
    menu_getExcel: {
        _attributes: {
            id: "menuGetExcel",
            class: "menu-close"
        },
        li: [
            {
                button_close: {
                    _attributes: {
                        text: "Fenster schließen",
                        id: "closeOutputBox_getExcel",
                        addEvent: await generalHeaderEvents.getData("closeOutputBox_getExcel")
                    }
                }
            }
        ]
    }
});

export default await header.getData("headerCode");