import {
    PartarumSurfaceCollector as PSC
} from "./PartarumSurfaceCollector.js";

class PartarumWorkerConnector {

    /**
     *
     * @returns {Promise<PartarumSurfaceTopicStore | Error>}
     *
     * @todo Muss direkt in Partarum implementiert werden
     */
    static #setBase() {

        return new Promise(async (resolve, reject) => {

            try {

                /**
                * @type {PartarumSurfaceTopicStore}
                * @description Den Worker Cache initialisieren
                */
                window.PartarumWorker = await PSC.init("Worker");

                (await this.#initBeerWorker()) && resolve();

            } catch( error ) {

                reject(error);
            }
        });
    }

    /**
     *
     * @param {Object} directory Angabe aller Adressen, welche mit einem fetch kontaktiert werden sollen
     * @returns {Promise<PartarumSurfaceTopicStore | Error>}
     */
    static init(directory) {

        return new Promise(async (resolve, reject) => {

            try {

                await this.#setBase();

                await this.#initBackgroundFetcher(directory);

                resolve(window.PartarumWorker);

            } catch (error) {

                reject(error);
            }
        });
    }

    /**
     *
     * @returns {Promise<boolean | Error>}
     */
    static #initBeerWorker() {

        return new Promise(async (resolve, reject) => {

            try {

                const beerWorker = await window.PartarumWorker.setTheme("BeerWorker");

                await beerWorker.setData("executive", new Worker("./Partarum/PartarumJS/Worker/PartarumBeerWorker.js"));

                await beerWorker.setData("callHome", (message, expires = 0) => {

                    return new Promise(async (resolveProm_1, rejectProm_1) => {

                        let worker = await beerWorker.getData("executive");

                        worker.postMessage({
                            message: message,
                            expires: expires
                        });

                        worker.onmessage = (event) => {
                            resolveProm_1(event.data);
                        }
                    });
                });

                await beerWorker.setData("holdBeer", (expires, tokenName = null) => {

                    return new Promise(async (resolveProm_2, reject_Prom_2) => {

                        (await beerWorker.getData("callHome"))("holdBeer", expires).then((data) => {

                            sessionStorage.setItem(tokenName ?? data[0], data[1]);

                            resolveProm_2(data);
                        });
                    });

                });

                await beerWorker.setData("tasteBeer", () => {

                    return new Promise(async (resolveProm_3, rejectProm_3) => {

                        (await beerWorker.getData("callHome"))("tasteBeer").then((data) => {
                            resolveProm_3(data);
                        });
                    });
                });

                resolve(true);

            } catch (error) {

                reject(error);
            }
        });

    }

    static #initBackgroundFetcher(directory) {

        return new Promise(async (resolve, reject) => {

            try {

                /**
                 *
                 * @type {PartarumSurfaceThemeStore|Error}
                 *
                 * @todo Später in Partarum implementieren
                 *
                 * @description Eine Verbindung zum Worker, welche sämtliche fetches durchführt, an die Adressen, welche man ihn per initWorker mitgibt
                 */
                const backgroundFetcher = await window.PartarumWorker.setTheme("BackgroundFetcher");

                await backgroundFetcher.setData("executive", new Worker("./Partarum/PartarumJS/Worker/PartarumBackgroundFetcher.js"));

                await backgroundFetcher.setData("initWorker", async (directory) => {

                    return new Promise(async (resolveProm_1, rejectProm_1) => {

                        try {

                            let worker = await backgroundFetcher.getData("executive");

                            worker.postMessage({
                                message: directory,
                                option: null,
                                expires: null,
                                requestType: "init",
                                responseType: null
                            });

                            worker.onmessage = (event) => {
                                resolveProm_1(event.data);
                            }

                        } catch( error ) {

                            rejectProm_1(error);
                        }
                    });
                });

                await backgroundFetcher.setData("callHome", (message, option, requestType = "form", responseType = "json", expires = 0) => {

                    return new Promise(async (resolveProm_2, rejectProm_2) => {

                        try {

                            let worker = await backgroundFetcher.getData("executive");

                            worker.postMessage({
                                message: message,
                                option: option,
                                expires: expires,
                                requestType: requestType,
                                responseType: responseType
                            });

                            worker.onmessage = (event) => {
                                resolveProm_2(event.data);
                            }

                        } catch( error ) {

                            rejectProm_2(error);
                        }
                    });
                });

                let lastAwait = await (await backgroundFetcher.getData("initWorker"))(directory);

                resolve(true);
            } catch( error ) {

                reject(error);
            }
        });
    }
}

export { PartarumWorkerConnector };