import {Partarum} from  "../../../Partarum/PartarumJS/ClientSide/Partarum.js";
import {
    PartarumSurfaceCollector as PSC
} from "../../../Partarum/PartarumJS/ClientSide/PartarumSurfaceCollector.js";

import {
    PartarumWorkerConnector as PWC
} from "../../../Partarum/PartarumJS/ClientSide/PartarumWorkerConnector.js";

import surface from "/surface/import";

/**
 *
 * @type {Partarum2}
 */
let app = new Partarum();

const workerInterface = await PWC.init({
    checkSurvey: location.origin + "/API/V1/dashboard/checkSurvey",
    startSurvey: location.origin + "/API/V1/dashboard/startSurvey",
    stopSurvey: location.origin + "/API/V1/dashboard/stopSurvey",
    getExcelData: location.origin + "/API/V1/dashboard/getExcelData",
    getExcelFile: location.origin + "/API/V1/dashboard/getExcelFile"
});

const beerWorker = await workerInterface.getTheme("BeerWorker");

const backgroundFetcher = await window.PartarumWorker.getTheme("BackgroundFetcher");

await (await beerWorker.getData("holdBeer"))(1800).then(async (beer) => {

    sessionStorage.setItem("startBrowserSession", Date.now().toString());

    /**
     *
     * @type {PartarumSurfaceTopicStore|Error}
     */
    const pscDashboard = await PSC.init("Dashboard");

    const pscRequests = await PSC.init("Requests");

    const pscResponses = await PSC.init("Responses");

    const pscSurface = await PSC.init("Surface");

    const pscDialogues = await PSC.init("Dialogues");



    const surfaceHelper = await pscSurface.setTheme("Helper");

    await surfaceHelper.setData("date-humanized", (dateSting) => {

        return new Promise((resolve, reject) => {

            const date = new Date(dateSting + "T00:00:00");

            const day = date.getDate();

            const month = date.getMonth() + 1;

            const year = date.getFullYear();

            resolve(`${day}.${month}.${year}`);
        });
    });

    await surfaceHelper.setData("date-input", (dateSting = null) => {

        return new Promise((resolve, reject) => {

            const date = (dateSting !== null) ? new Date(dateSting + "T00:00:00") : new Date();

            const day = date.getDate();

            const month = date.getMonth() + 1;

            const year = date.getFullYear();

            resolve(`${year}-${month.toString().padStart(2,"0")}-${day.toString().padStart(2,"0")}`);
        });
    });

    /**
     *
     * @type {PartarumSurfaceThemeStore|Error}
     */
    const dashboardForms = await pscRequests.setTheme("formOptions");

    await dashboardForms.setData("regular", {
        method: "POST",
        headers: {
            "Authorization": "Bearer " + sessionStorage.getItem("beer")
        }
    });

    const formFetcher = await pscRequests.setTheme("formFetcher");


    await formFetcher.setData("FormDataForWorker", async (id) => {

        let formElement = document.getElementById(id);

        if (formElement instanceof HTMLFormElement) {

            let options = await dashboardForms.getData("regular");

            let formBody = new FormData(formElement);

            let objectBody = {};

            for (const [key, value] of formBody.entries()) {

                objectBody[key] = value;
            }

            options.body = objectBody;

            return options;

        }

    });

    await formFetcher.setData("basic", (id, name) => {

        return new Promise(async (resolve, reject) => {

            await (await formFetcher.getData("FormDataForWorker"))(id).then( async (options) => {

                await (await backgroundFetcher.getData("callHome"))(name, options, "form", "json").then((json) => {

                    resolve(json);
                }).catch((error) => {

                    reject(error);
                });
            });
        });
    });


    await formFetcher.setData("excel", (id, name, style) => {

        return new Promise(async (resolve, reject) => {

            await (await formFetcher.getData("FormDataForWorker"))(id).then( async (options) => {

                options.body["style"] = style;

                options.body["type"] = "test";

                await (await backgroundFetcher.getData("callHome"))(name, options, "form", "text").then(async (text) => {

                    if(text === "hallo") {

                        options.body["type"] = "download";

                        await (await backgroundFetcher.getData("callHome"))(name, options, "form", "blob").then((blob) => {

                            const url = window.URL.createObjectURL(new Blob([blob]));
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = 'Erhebung.xlsx';
                            document.body.appendChild(a);
                            a.click();

                            console.dir("Remove Link");

                            a.remove();

                            resolve(blob);

                        }).catch((error) => {

                            reject(error);
                        });
                    }

                }).catch((error) => {

                    reject(error);
                });
            });
        });
    });

    app.themes = [
        {
            header: {
                config: surface.dashboardPage.header.container,
                parent: document.getElementById("header")
            }
        },
        {
            landingPage: {
                config: surface.dashboardPage.main.container,
                parent: document.getElementById("content")
            }
        },
        {
            footer: {
                config: surface.dashboardPage.footer.container,
                parent: document.getElementById("footer")
            }
        }
    ];
    app.create(() => {}).then(async () => {

        const dialoguesGlobal = await pscDashboard.setTheme("Global");

        const sessionIsOver = await dialoguesGlobal.setData("dialogSessionIsOver", () => {

            document.getElementById("dialogSessionIsOver").showModal();

            setTimeout(() => {
                location.replace("/loginPage")
            }, 50000);
        });

        setTimeout(await dialoguesGlobal.getData("dialogSessionIsOver"), 1800000);
    });
});