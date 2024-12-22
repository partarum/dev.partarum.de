class PartarumSurfaceThemeStore {

    store = new Map();

    constructor() {

    }

    clear() {
        this.store.clear();
    }

    deleteData(name) {

    }

    /**
     *
     * @param {string} name
     * @returns {Promise<any | Error>}
     */
    getData(name) {

        return new Promise((resolve, reject) => {
           try {

               resolve((this.hasData(name)) ? this.store.get(name) : null);

           } catch(error) {

               reject(error);
           }
        });
    }

    hasData(name) {
        return this.store.has(name);
    }

    /**
     *
     * @param { string } name
     * @param { any } value
     * @returns {Promise<boolean | Error>}
     */
    setData(name, value) {

        return new Promise((resolve, reject) => {

            try {

                (this.store.set(name, value)) && resolve(true);

            } catch(error) {

                reject(error);
            }
        });
    }
}

class PartarumSurfaceTopicStore {

    themeStore = new Map();

    constructor(objectStore) {

    }

    clear() {
        this.themeStore.clear();
    }

    deleteTheme() {

    }

    /**
     *
     * @param theme
     * @returns {Promise<PartarumSurfaceThemeStore | null | Error>}
     */
    getTheme(theme) {

        return new Promise((resolve, reject) => {

            try {

                resolve((this.hasTheme(theme)) ? this.themeStore.get(theme) : null);

            } catch(error) {

                reject(error);
            }
        });
    }

    hasTheme(theme) {
        return this.themeStore.has(theme);
    }

    /**
     *
     * @param {string} theme
     * @returns {Promise<PartarumSurfaceThemeStore | Error>}
     */
    setTheme(theme) {

        return new Promise((resolve, reject) => {

            try {

                let themeStore = new PartarumSurfaceThemeStore();

                (this.themeStore.set(theme, themeStore)) && resolve(themeStore);

            } catch(error){

                reject(error);
            }
        });
    }
}

/*
    1. PSC initialisieren und ggf. ein Topic mitgeben
        1.1 Topic = "dashboard"
        1.2 Topic initialisieren
        1.3 Wenn Topic nicht mitgegeben wurde, dann gibt es das PSC zurück und es muss zur Initialisierung PSC.createStore ausgeführt werden
 */

/** Class - the heart of Surface */
class PartarumSurfaceCollector {
    /**
     * @type {WeakSet}
     */
    #wm;

    /**
     *
     * @type {Map<any, any>}
     */
    #storeNameCache = new Map();

    /**
     * One time to start the WeakSet
     */
    constructor(){

        this.#wm = new WeakSet();

    }

    /**
     *
     * @param {string | null} store
     * @returns {Promise<PartarumSurfaceTopicStore | Error>}
     */
    static init(store = null) {

        return new Promise((resolve, reject) => {

            try {
                let psc;

                if(!Reflect.has(Partarum, "GlobalPSC")){

                    psc = new PartarumSurfaceCollector();

                    Reflect.set(Partarum, "GlobalPSC", psc);
                } else {

                    psc = Reflect.get(Partarum, "GlobalPSC");
                }

                if(store !== null){

                    resolve( (psc.hasTopicStore(store)) ? psc.getTopicStore(store) : psc.createTopicStore(store) );
                } else {

                    resolve( psc );
                }
            } catch(error) {

                reject( error );
            }

        });
    }

    clear() {
        //this.#wm;

        this.#storeNameCache.clear();
    }

    deleteTopicStore(store) {

    }

    getTopicStore(store) {

        return (this.hasTopicStore(store)) ? this.#storeNameCache.get(store) : null;
    }

    hasTopicStore(store) {
        return this.#storeNameCache.has(store);
    }

    /**
     *
     * @param topic
     * @returns {PartarumSurfaceTopicStore}
     */
    createTopicStore(topic) {

        let os = new PartarumSurfaceTopicStore();

        this.#wm.add(os, topic);

        this.#storeNameCache.set(topic, os);

        return os;
    }
}

export { PartarumSurfaceCollector };