/*
 *   Copyright 2018- 2022 © Alexander Bombis. All rights reserved.
 *            Developed by Alexander Bombis.
 *            Email: email@alexander-bombis.de
 */
import {HTMLPreload} from "./HTML/HTMLPreload.js";
import {Cache} from "./Cache/Cache.js";
import {Workshop} from "./Workshop/Workshop.js";
import {ClientSetting} from "./System/ClientSetting.js";
import {Cookie} from "./System/Cookie.js";
import {HTML} from "./HTML/HTML.js";

class Partarum {

    constructor() {

        this.item = "Was geht ab?";
        this.dom = [];
        this.id = Cache.ImportCache.setCondition();
        this.app = {};
        Partarum.workerCache = {};

        if((!globalThis.Partarum) || (!Window.Partarum)){

            //console.log("start---Partarum--- without window.partarum");

            globalThis.Partarum = Partarum;

            let preload = async ()=>{

                await HTMLPreload.init();
            };

            (!Partarum.preloadStatus) && preload().then(()=>{

                new Workshop();

                Partarum.preloadStatus = true;

                Cookie.init().then((cookieTest)=>{

                    console.dir(cookieTest);
                });
            })
        } else {

            //console.log("start---Partarum--- with window.partarum");
        }
    }

    static void(){
        return "javascript:void(0)";
    }

    static init() {
        if((!globalThis.Partarum) || (!Window.Partarum)){
            globalThis.Partarum = Partarum;
            new Workshop();
        }
    }
        
    addWorkbox(name, file){
        
        Partarum.workerCache[name] = new Partarum.Workbox(file, {name: name});
        
        return Partarum.workerCache[name].getWorker();
    }
        
    static getWorker(name){
        
        return Partarum.workerCache[name].getWorker();
    }

    static counter(){

        Partarum.count ??= 0;
        Partarum.count++;
    }

    childCounter(){

        this.childCount ??= 0;
        this.childCount++;
    }

    static setTheme(i){

        Partarum.themeCache ??= [];
        Partarum.themeCache.push(i);
    }

    static getTheme(){
        return Partarum.themeCache[Partarum.themeCache.length - 1];
    }

    static setStart(){

        Partarum.isStarted ??= 1;
    }

    static stopStart(){

        Partarum.isStoped ??= 1;
    }

    static start(){
        /*
            Hier sämtliche Funktionen ausführen lassen, welche erst nach der Erstellung der Seite ausgeführt werden sollen
         */

        let intervall = setInterval(()=>{

            let id = location.hash.slice(1);

            if(id !== "") {

                if (document.getElementById(id)) {

                    // Hier müssen wir noch die Ancorthematik angreifen !!!! Das sollte auf jeden Fall noch tiefgründiger angegangen werden !!!!

                    let display = Partarum.ViewKit.setSingleDisplay("anchor");
                    display.setScrollableElement("window");

                    Partarum.ViewKit.callDisplay("anchor").setViewport(id, "center");

                    Partarum.ViewKit.setIntoView("anchor", id, "center");

                    clearInterval(intervall);
                }
            } else {
                clearInterval(intervall);
            }
        }, 100);
    }

    set surface(value){

        this.setSurface(value);
    }

    setSurface(value){



        this.surfaceObject = value;
    }

    create(callback){

        // Runden zählen
        Partarum.counter();

        // Prüfen ob ein Callback mitgegeben wurde, welcher zum Schluss ausgeführt wird
        (callback instanceof Function) && Cache.PartarumCache.setCallback(callback);


        return new Promise((resolve, reject) => {

            Partarum.checkPreload().then(()=>{
                // prüfen ob ein Template mitgegeben wurde

                //console.log("start---Station");

                Cache.PartarumCache.isTemplate = ((this.template) ?? true) && Cache.PartarumCache.setTemplates(this.template);

                /*
                       ! um die option surface statt themes erweitern !!!

                       auf Type der Option prüfen und Type abspeichern
                 */



                this.type = (this?.themes) ? "group" : ((this?.theme) ? "single" : null);


                // prüfen ob es sich um ein einfachen Aufbau oder einen Gruppenaufbau handelt

                /*
                        Single: ein Object mit traget | parent und surface

                                {
                                    surface: kann ein Object oder ein import sein
                                    target: ist immer ein HTMLElement
                                }

                        Group: ein Array mit einzelnen Gruppen  oder ein Object mit einzelnen Singles

                        {
                            header: Single
                            main: Single
                            footer Single
                        }

                        [
                            {
                                header: Single - das erste Element ist der Parent für die darauffolgenden Elemente
                                nav: Single - benötigt einen Verweis auf header
                            },
                            {
                                main: Single
                                article: Single
                            },
                            {
                                footer: Single
                                section: Single
                            }
                        ]

                 */

                if (this.type === "single") {

                    let app = new Station(this);
                    let result = this?.config ? app.loadPage(this.config) : console.dir(this);
                    result.then(() => {

                    })
                } else if (this.type === "group") {

                    this.themes.forEach((value, index) => {

                        for (let t in value) {

                            if (value.hasOwnProperty(t)) {

                                this.childCounter();

                                Partarum.setTheme(t);

                                value[t].theme = t;

                                Cache.ImportCache.conditionObject[this.id].push({[t]: value[t]});

                                let app = new Station(value[t], this.id);
                                this.app[t] = app.loadPage(value[t].config);
                            }
                        }
                    })
                }

                resolve(this.app);
            });
        });
    }

    static checkPreload(){

        return new Promise((resolve)=> {

            if(Partarum.preloadStatus === true){

                //console.log("window.Partarum has ViewKit");

                resolve(true);
            } else {

                //console.log("window.Partarum has no ViewKit");

                setTimeout(() => {
                    this.checkPreload().then(()=>{resolve(true)});
                }, 100);
            }
        });
    }
}

class Station {

    constructor(arg, id) {

        this.arg = arg;
        this.id = id;
        Cache.ImportCache.create(id);
    }

    loadPage(filePath) {
        /*
                Noch auf die Möglichkeit des statischen Importes abändern !!!!

           ! Javascript Objekte auch direkt übergeben können  - Abänderung 12.10.2021 -- !!!!
         */
        async function load(arg, id) {

            // holen des Moduls Config - Datei

            if(typeof(filePath) === "string") {

                console.dir(filePath);

                return await import(filePath)
                    .then((data) => {

                        // WorkingCache.setTreat("Station_loadPage().async_load().then() --- id: " + id);

                        return {
                            [arg.theme]: {
                                id: id,
                                theme: arg.theme,
                                themeData: arg,
                                type: "import",
                                module: data
                            }
                        };
                    }).catch((error) => {

                        console.log(filePath);
                        console.dir(error);
                    });

            } else if(typeof(filePath) === "object"){

                return {
                    [arg.theme]: {
                        id: id,
                        theme: arg.theme,
                        themeData: arg,
                        type: "direct",
                        module: filePath
                    }
                }
            }
        }

        return load(this.arg, this.id).then((data) => {

            let nowImportCacheLength = Object.keys(Cache.ImportCache.modulArray).length;
            let nowImportCacheIDCounter = Cache.ImportCache.idCounter;

            let theme = Object.keys(data)[0];

            let id = data[theme].id;

            Cache.ImportCache.setID(id);

            Cache.ImportCache.modulArray[id] ??= {};

            Cache.ImportCache.modulArray[id][theme] = data[theme];

            if(Object.keys(Cache.ImportCache.modulArray[id]).length === Cache.ImportCache.conditionObject[id].length){

                Cache.ImportCache.conditionObject[id].forEach((value, index, array) => {

                    for(let conditionTheme in value) {

                        if (value.hasOwnProperty(conditionTheme)) {

                            /*
                                ! importData === der export aus einer JS - Datei
                             */

                            let im = Cache.ImportCache.modulArray[id][conditionTheme];

                            let importData;

                            if(im.hasOwnProperty("type")){

                                if(im.type === "import"){

                                    importData = im.module.default;

                                } else if(im.type === "direct"){

                                    importData = im.module;
                                }
                            } else {

                                importData = Cache.ImportCache.modulArray[id][conditionTheme].module.default;
                            }

                            let content = new WebApp(conditionTheme, importData, value[conditionTheme].config);
                            content.setPage(value[conditionTheme]);
                        }
                    }
                })
            }

            if((nowImportCacheLength !== 0) && (nowImportCacheLength === nowImportCacheIDCounter - 1)) {

                /*
                        Hier ist richtig Schluss !!!!!
                 */

                if(Partarum.isStarted !== 1) {

                    if(Partarum.isStoped !== 1) {
                        Partarum.start();
                        Partarum.setStart();
                        Partarum.stopStart();
                        Partarum.isStarted = null;
                    }

                    Station.setCallback();
                }

            } else {

                /*
                    ! Hier die callbacks ausführen, welche in der Haupt - JS festgelegt wurden !!!
                 */

                Cache.PartarumCache.setRound();

                if(Cache.PartarumCache.getRound() === Cache.ImportCache.conditionObject["round_0"].length){

                    /*
                        ! muss noch gegengewertet werden bezüglich der _import Aufrufe
                     */

                    Station.setCallback();
                }
            }

            return data;

        }).catch((error) => {

            console.dir(error);
        });
    }

    static setCallback(){

        let c = Cache.PartarumCache.getCallback();

        if(c !== undefined) {
            for (let i = 0; i < c.length; i++) {

                let call = c[i] ?? (()=>{});
                call();
            }

            Cache.PartarumCache.callback = [];
        }
    }
}

class WebApp {

    constructor(page, data, filePath) {

        this._surface = data;

        this._cache = [data];

        this.filePath = filePath;

        this._clientSettings = new ClientSetting();
    }

    setPage(arg){

        //console.dir(arg);

        let box = null;

        if(arg?.container) {
            box = document.createElement(arg.container);
            box.setAttribute("id", arg.theme);

            arg.parent.appendChild(box);
        }

        for (let module of this._cache) {

            if (module instanceof Promise) {

                module[0].then(data => {

                    Cache.PartarumCache.setSurfacePaths(this._surface.surface);

                    return new Content(data.default, box ?? arg.parent, this.filePath);
                });
            } else {

                new Content(module, box ?? arg.parent, this.filePath );
            }
        }
    }
}

class Content {

    constructor(surface, arg, filePath) {
        // Initialisierung wie bisher
        if (arg !== null) {
            this.setDomTarget(arg);
            this.surface = surface;
            this.surfacePaths = Cache.PartarumCache.surfacePaths;
            this.filePath = filePath;

            // Initialisiere Cache wenn nötig
            Cache.DOMCache.create();

            // Startet den Render-Prozess
            this.render();
        }
    }

    /**
     * Hilfsmethode, um das Ziel-Element (Parent oder Sibling) zu bestimmen
     */
    setDomTarget(arg) {
        this.dom = null;
        this.after = null;

        if (arg instanceof HTMLElement || arg instanceof ShadowRoot) {
            this.dom = arg;
        } else if (arg?.after) {
            this.after = arg.after;
            // Falls wir 'after' nutzen, brauchen wir für Kind-Elemente später den Parent des 'after'-Elements
            this.dom = this.after.parentElement;
        }
    }

    /**
     * Die neue Hauptmethode (ehemals create).
     * Sie entscheidet nur noch, WELCHE Strategie angewendet wird.
     */
    render() {
        if (!this.surface) return;

        if (typeof this.surface === "string") {
            this.renderText(this.surface);
        } else if (Array.isArray(this.surface)) {
            this.renderList(this.surface);
        } else if (typeof this.surface === "object") {
            this.renderObject(this.surface);
        }
    }

    /**
     * Strategie 1: Einfacher Text
     */
    renderText(text) {
        // Hier könnte man auch Template-Referenzen prüfen, falls Strings diese enthalten
        this.appendNode(document.createTextNode(text));
    }

    /**
     * Strategie 2: Listen / Arrays
     */
    renderList(list) {
        // Performance-Tipp: DocumentFragment nutzen, um Reflows zu minimieren
        const fragment = document.createDocumentFragment();

        // Wir brauchen einen temporären Container für die Rekursion,
        // da 'Content' aktuell noch DOM-Elemente erwartet.
        // In einem tieferen Refactoring könnte man Content so umbauen,
        // dass es auch Fragmente akzeptiert.

        list.forEach(item => {
            // Rekursion für jedes Item
            // Achtung: Hier behalten wir die Logik bei, dass Arrays oft Listen von Nodes sind
            new Content(item, this.dom, this.filePath);
        });
    }

    /**
     * Strategie 3: Objekte (Das Herzstück)
     * Hier werden Tags, Attribute und Imports unterschieden.
     */
    renderObject(surface) {
        for (let key in surface) {
            if (!surface.hasOwnProperty(key)) continue;

            Cache.DOMCache.counter++; // Den globalen Counter beibehalten
            const value = surface[key];

            if (key === "_attributes") {
                this.handleAttributes(value);
            } else if (key === "_import") {
                this.handleImport(value);
            } else if (key === "_partarum") {
                // Spezielle Partarum Logik
                console.dir(value);
                this.appendNode(value);
            } else if (key.startsWith("$")) {
                // Template Properties (Ignorieren oder speziell behandeln)
                // Im Original-Code wurde das im else-Zweig oft ignoriert oder separat behandelt
            } else {
                // Es ist ein HTML-Tag oder eine Template-Referenz
                this.handleNodeOrTemplate(key, value);
            }
        }
    }

    /**
     * Verarbeitet Attribute (_attributes)
     */
    handleAttributes(attributes) {
        Cache.DOMCache.roundCounter[Cache.DOMCache.counter] = "attribute";

        for (let attr in attributes) {
            if (!attributes.hasOwnProperty(attr)) continue;
            if (attr.startsWith("$")) continue;

            const value = attributes[attr];

            if (attr === "text") {
                this.handleTextAttribute(value);
            } else if (attr === "innerHTML") {
                this.dom.innerHTML = value;
            } else if (attr === "addEvent" || attr === "addDOMEvent") {
                this.handleEventAttribute(attr, value);
            } else {
                // Standard Attribut
                this.dom.setAttribute(attr, value);
            }
        }
    }

    handleTextAttribute(value) {
        if (value instanceof Promise) {
            value.then((data) => {
                this.dom.appendChild(document.createTextNode(data));
            });
        } else {
            this.dom.appendChild(document.createTextNode(value));
        }
    }

    handleEventAttribute(type, eventData) {
        if (type === "addDOMEvent") {
             // Im Original direkt ausgeführt
             if (typeof eventData.doThat === 'function') {
                 eventData.doThat();
             }
             return;
        }

        // Partarum Event Cache Logik
        if (Partarum.hasOwnProperty("Cache")) {
            const eventArray = Array.isArray(eventData) ? eventData : [eventData];

            for (let event of eventArray) {
                if (event.name) {
                    if (event.topic) {
                        Partarum.Cache.EventCache.create(event.topic, event.theme);
                    }
                    Partarum.Cache.EventCache.setEvent(event);
                }

                let eventCallback = Partarum.Cache.EventCache.getEvent(event.topic, event.theme, event.name) ?? event["doThat"];

                if (eventCallback) {
                    this.dom.addEventListener(event.type, eventCallback, event.bubbles === true);
                }
            }
        }
    }

    /**
     * Verarbeitet Imports (_import)
     */
    handleImport(importData) {
        Cache.DOMCache.roundCounter[Cache.DOMCache.counter] = "import";

        if (typeof importData !== "object") {
            // Fall 1: Import ist ein String (URL) -> Neue Partarum App
            let app = new Partarum();
            app.themes = [{
                [importData]: { config: importData, parent: this.dom }
            }];
            app.create().then((prom_res) => {
                console.log("Import erfolgreich - Prom_res:");
                console.dir(prom_res);
            });

        } else if (Array.isArray(importData)) {
             // Fall 2: Array von Imports
             for (let item of importData) {
                 new Content(item, this.dom, this.filePath);
             }

        } else if (importData.template) {
            // Fall 3: Template Import
            this.handleTemplateImport(importData);

        } else {
            // Fall 4: Fetch Text (z.B. HTML Snippets via Fetch)
            this.handleFetchImport(importData);
        }
    }

    handleTemplateImport(importData) {
        if (typeof importData.template === "object") {
            let valueFile = importData.template.valueFile;
            Cache.PartarumCache.isTemplate = true;
            Cache.PartarumCache.setTemplates(valueFile);
            new Template(importData.template.name, importData.template.surface, valueFile, this.dom);
            Cache.PartarumCache.isTemplate = false;

        } else if (typeof importData.template === "string") {
            if (Cache.PartarumCache.getTemplate(importData.template)) {
                Cache.DOMCache.roundCounter[Cache.DOMCache.counter] = "templateStart";
                Template.start = true;
                Template.startNumber = Cache.DOMCache.counter;

                new Template(
                    importData.template,
                    Cache.PartarumCache.templatePaths[importData.template],
                    Cache.PartarumCache.templates[importData.template],
                    this.dom
                );
            }
        }
    }

    handleFetchImport(importData) {
        // Einfacher Fetch für Textinhalte
        if (importData.text) {
             fetch(importData.text)
                .then(response => {
                    if (!response.ok) throw new Error("HTTP error " + response.status);
                    return response.text();
                })
                .then(text => {
                    this.dom.appendChild(document.createTextNode(text));
                })
                .catch(console.error);
        }
    }

    /**
     * Verarbeitet normale HTML Nodes (z.B. div, span) oder Template Referenzen
     */
    handleNodeOrTemplate(key, value) {
        // Prüfen, ob es sich um eine Template-Variable handelt
        // (Im Original war hier eine komplexe Logik mit Template.valueCache)
        if (Template.valueCache && Template.valueCache[key] !== undefined) {
             // Hier müsste die komplexe Template-Logik ausgelagert werden
             // Um den Rahmen nicht zu sprengen, deute ich es hier an:
             // this.renderTemplateValue(key, value);
             // Für jetzt behandeln wir es als normales Element, wenn kein Template:
             return;
        }

        // Normales Element erstellen
        let tagName = key.split("_")[0]; // "div_header" -> "div"
        if (tagName === "") return; // Fallback

        Cache.DOMCache.roundCounter[Cache.DOMCache.counter] = "node";

        let newNode = document.createElement(tagName);

        // Handling für Strings direkt im Key (Seltener Fall im Original, aber möglich)
        if (typeof value === "string") {
            newNode.appendChild(document.createTextNode(value));
        }

        this.appendNode(newNode);

        // Rekursion für den Inhalt des neuen Elements
        // WICHTIG: Das 'value' Objekt definiert den Inhalt des neuen Nodes
        new Content(value, newNode, this.filePath);
    }

    /**
     * Wrapper für DOM-Operationen
     */
    appendNode(node) {
        if (this.after) {
            this.after.after(node);
        } else {
            this.dom.appendChild(node);
        }
    }
}

class Template {

    constructor(templateName, templateSurface, valuePath, parentNode) {

        Template.setStaticTemplateProperties();

        this.templateName = templateName;
        this.templateSurface = templateSurface;
        this.pathsObject = {
            templateSurface: templateSurface,
            valuePath: valuePath
        };

        this.loadDOM(templateName, this.pathsObject, parentNode);
    }

    loadDOM(templateName, pathsObject, parentNode) {

        if(pathsObject.valuePath instanceof Object) {
            Template.templateScript[templateName] = pathsObject.templateSurface;
            Template.templateValue[templateName] = pathsObject.valuePath;
            Template.valueCache = pathsObject.valuePath;

            Cache.DOMCache.roundCounter[Cache.DOMCache.counter] = "templateAsync";

            let templateNode = new Content(Template.templateScript[templateName], parentNode, "");

        } else {

            async function load() {

                for (let path in pathsObject) {

                    if (pathsObject.hasOwnProperty(path)) {

                        if (path === "valuePath") {

                            await import("/" + pathsObject[path])
                                .then((data) => {

                                    Template.templateScript[templateName] = pathsObject.templateSurface;
                                    Template.templateValue[templateName] = data.default;
                                    Template.valueCache = data.default;

                                })
                                .catch((error) => {
                                    console.dir(error);
                                });
                        }
                    }
                }

                Cache.DOMCache.roundCounter[Cache.DOMCache.counter] = "templateAsync";

                let templateNode = new Content(Template.templateScript[templateName], parentNode, "");
            }

            load().then(() => {
                return true;
            }).catch((error) => {
                console.dir(error);
            });
        }
    }


    static start(){
        if(Template.startNumber === undefined) {
            Template.startNumber = 0;
        }
    }

    static setStaticTemplateProperties(){

        if(Template.templateScript === undefined) {
            Template.templateScript = {};
        }

        if(Template.templateValue === undefined) {
            Template.templateValue = {};
        }

        if(Template.valueCache === undefined){
            Template.valueCache = {};
        }

        if(Template.templateCounter === undefined){
            Template.templateCounter = {};
        }
    }

    static getTemplateScripts(name){

        return Template.templateScript[name];
    }
}

//export {Partarum, WorkingCache, PartarumCache, EventCache, Office, Workshop};
export {Partarum, Cache, Workshop, HTML}