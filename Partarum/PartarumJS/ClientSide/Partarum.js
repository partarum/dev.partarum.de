/*
 * Copyright 2018- 2025 © Alexander Bombis. All rights reserved.
 * Developed by Alexander Bombis.
 * Email: email@alexander-bombis.de
 */
import {HTMLPreload} from "./HTML/HTMLPreload.js";
import {Cache} from "./Cache/Cache.js";
import {Workshop} from "./Workshop/Workshop.js";
import {ClientSetting} from "./System/ClientSetting.js";
import {Cookie} from "./System/Cookie.js";
import {HTML} from "./HTML/HTML.js";

// ============================================================================
// 1. PARTARUM (Der Manager)
// Steuert den Ablauf: Init -> Laden (Station) -> Rendern (Content)
// ============================================================================
class Partarum {

    constructor(config) {
        // Option 1: Config direkt im Constructor übergeben
        if (config) {
            Object.assign(this, config);
        }

        this.app = {};
        this.dom = [];

        // Initialisierung des globalen Scopes
        if ((!globalThis.Partarum) || (!window.Partarum)) {
            globalThis.Partarum = Partarum;
            this.initSystem();
        }
    }

    async initSystem() {
        // Preloader und Basis-Systeme starten
        await HTMLPreload.init();
        new Workshop();
        await Cookie.init().then(cookieTest => {
            // console.dir(cookieTest);
        });
        Partarum.preloadStatus = true;
    }

    // --- Statische Helper ---
    static void() { return "javascript:void(0)"; }

    static counter() {
        Partarum.count = (Partarum.count || 0) + 1;
    }

    static setTheme(i) {
        Partarum.themeCache = Partarum.themeCache || [];
        Partarum.themeCache.push(i);
    }

    /**
     * Die Hauptmethode: Startet die App
     */
    async create(callback) {
        Partarum.counter();

        // 1. Preload abwarten
        if (!Partarum.preloadStatus) {
            await this.initSystem();
        }

        // 2. Templates laden (falls global definiert)
        if (this.template) {
            Cache.PartarumCache.setTemplates(this.template);
        }

        // 3. Typ bestimmen
        this.type = (this.themes) ? "group" : ((this.theme) ? "single" : "unknown");
        const station = new Station();

        // --- SINGLE PAGE LOGIK ---
        if (this.type === "single" && this.config) {

            const pageData = await station.loadPage(this.config, this.theme);

            if (pageData) {
                this.renderPage(this.theme, pageData, this);
            }
        }

        // --- GROUP PAGE LOGIK ---
        else if (this.type === "group" && this.themes) {

            // Parallel laden mit Promise.all
            const loadPromises = this.themes.map(async (themeConfig) => {
                // Key-Value Extraktion (z.B. { header: {...} })
                const themeName = Object.keys(themeConfig)[0];
                const configData = themeConfig[themeName];

                Partarum.setTheme(themeName); // Globalen Theme-Stack pflegen

                const pageData = await station.loadPage(configData.config, themeName);

                if (pageData) {
                    this.app[themeName] = pageData;
                    // Wir merken uns die Config im Resultat für späteres Rendering
                    pageData._renderConfig = configData;
                    return pageData;
                }
            });

            const results = await Promise.all(loadPromises);

            // Nach dem Laden aller Teile -> Rendern
            results.forEach(pageData => {
                if(pageData) {
                    this.renderPage(pageData.theme, pageData, pageData._renderConfig);
                }
            });
        }

        // 4. CALLBACK AUSFÜHREN
        if (typeof callback === "function") {
            Cache.PartarumCache.setCallback(callback);
            callback();
        }

        return this.app;
    }

    /**
     * Verbindet Daten mit dem DOM (Ersetzt die alte WebApp Klasse)
     */
    renderPage(themeName, pageData, configData) {

        // Container bestimmen (wohin soll gerendert werden?)
        let parent = configData.parent || document.body;

        // Falls ein neuer Container erstellt werden soll
        if (configData.container) {
            const containerBox = document.createElement(configData.container);
            containerBox.id = themeName;

            // Falls parent ein Selector String ist (optionales Feature)
            if (typeof parent === "string") {
                parent = document.querySelector(parent) || document.body;
            }

            parent.appendChild(containerBox);
            parent = containerBox;
        }

        // Cache für Module-Pfade setzen (Legacy Support)
        if (pageData.module && pageData.module.surface) {
             Cache.PartarumCache.setSurfacePaths(pageData.module.surface);
        }

        // Modul-Daten holen (Default Export beachten)
        const moduleContent = pageData.module?.default || pageData.module;

        // Content rendern
        new Content(moduleContent, parent, configData.config);
    }
}

// ============================================================================
// 2. STATION (Der Loader)
// Lädt Dateien asynchron und strukturiert die Rückgabe.
// ============================================================================
class Station {
    constructor() {
        // Initialisiert den Import-Cache
        Cache.ImportCache.create("system");
    }

    /**
     * Lädt Modul-Dateien
     */
    async loadPage(filePath, themeName = "default") {
        try {
            let loadedModule;

            if (typeof filePath === "string") {
                // Importiert die Datei
                loadedModule = await import(filePath);
            } else {
                // Direktes Objekt übergeben
                loadedModule = filePath;
            }

            // Strukturierte Rückgabe
            const payload = {
                id: (typeof filePath === "string") ? filePath : "object_" + Date.now(),
                theme: themeName,
                module: loadedModule,
                timestamp: Date.now()
            };

            // Im Cache speichern
            this.cacheModule(payload.id, themeName, payload);

            return payload;

        } catch (error) {
            console.error(`Station Error: Konnte ${filePath} nicht laden.`, error);
            return null;
        }
    }

    cacheModule(id, theme, data) {
        // Cache Logik (vereinfacht aus dem Original)
        Cache.ImportCache.setID(id);
        Cache.ImportCache.modulArray[id] = Cache.ImportCache.modulArray[id] || {};
        Cache.ImportCache.modulArray[id][theme] = data;
    }
}

// ============================================================================
// 3. CONTENT (Der Renderer)
// Wandelt JSON-Strukturen (Surface) rekursiv in DOM-Elemente um.
// ============================================================================
class Content {

    constructor(surface, arg, filePath) {

        // Initialisiere Template Cache, falls noch nicht passiert
        Cache.TemplateCache = Cache.TemplateCache || { currentValues: {} };

        if (arg !== null) {
            this.setDomTarget(arg);
            this.surface = surface;
            this.surfacePaths = Cache.PartarumCache.surfacePaths;
            this.filePath = filePath;

            // Initialisiere DOM Cache wenn nötig
            Cache.DOMCache.create();

            // Startet den Render-Prozess
            this.render();
        }
    }

    setDomTarget(arg) {
        this.dom = null;
        this.after = null;

        if (arg instanceof HTMLElement || arg instanceof ShadowRoot) {
            this.dom = arg;
        } else if (arg?.after) {
            this.after = arg.after;
            this.dom = this.after.parentElement;
        }
    }

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

    renderText(text) {
        this.appendNode(document.createTextNode(text));
    }

    renderList(list) {
        list.forEach(item => {
            new Content(item, this.dom, this.filePath);
        });
    }

    renderObject(surface) {
        for (let key in surface) {
            if (!surface.hasOwnProperty(key)) continue;

            Cache.DOMCache.counter++;
            const value = surface[key];

            // 1. Template Check (Cache Zugriff)
            const templateValues = Cache.TemplateCache?.currentValues;
            if (templateValues && templateValues[key] !== undefined) {
                this.handleTemplate(key, value);
                continue;
            }

            // 2. Spezial-Keys
            if (key === "_attributes") {
                this.handleAttributes(value);
            } else if (key === "_import") {
                this.handleImport(value);
            } else if (key === "_partarum") {
                console.dir(value);
                this.appendNode(value);
            } else if (key.startsWith("$")) {
                // Template Definitionen ignorieren
            } else {
                // 3. Normaler HTML Node
                this.handleNode(key, value);
            }
        }
    }

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
             if (typeof eventData.doThat === 'function') {
                 eventData.doThat();
             }
             return;
        }

        // Partarum Event Cache Logik
        if (Partarum.hasOwnProperty("Cache") || window.Partarum?.Cache) {
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

    handleImport(importData) {
        Cache.DOMCache.roundCounter[Cache.DOMCache.counter] = "import";

        if (typeof importData !== "object") {
            // URL String -> Neue App Instanz
            let app = new Partarum();
            app.themes = [{
                [importData]: { config: importData, parent: this.dom }
            }];
            app.create();

        } else if (Array.isArray(importData)) {
             for (let item of importData) {
                 new Content(item, this.dom, this.filePath);
             }

        } else if (importData.template) {
            this.handleTemplateImport(importData);

        } else if (importData.text) {
             fetch(importData.text)
                .then(r => r.text())
                .then(text => this.dom.appendChild(document.createTextNode(text)))
                .catch(console.error);
        }
    }

    handleTemplateImport(importData) {
        const processTemplate = (name, surface, valuePath) => {
            this.loadTemplateData(valuePath).then(loadedValues => {
                this.updateTemplateCache(name, surface, loadedValues);
                Cache.DOMCache.roundCounter[Cache.DOMCache.counter] = "templateAsync";
                new Content(surface, this.dom, this.filePath);
            });
        };

        if (typeof importData.template === "object") {
            const t = importData.template;
            processTemplate(t.name, t.surface, t.valueFile);
        } else if (typeof importData.template === "string") {
            const name = importData.template;
            if (Cache.PartarumCache.getTemplate(name)) {
                processTemplate(
                    name,
                    Cache.PartarumCache.templates[name],
                    Cache.PartarumCache.templatePaths[name]
                );
            }
        }
    }

    async loadTemplateData(valuePathOrData) {
        if (typeof valuePathOrData === "object") return valuePathOrData;
        if (typeof valuePathOrData === "string") {
            try {
                const path = valuePathOrData.startsWith("/") ? valuePathOrData : "/" + valuePathOrData;
                const module = await import(path);
                return module.default;
            } catch (error) { return {}; }
        }
        return {};
    }

    updateTemplateCache(name, surface, values) {
        Cache.TemplateCache = Cache.TemplateCache || {};
        Cache.TemplateCache.currentValues = values;
    }

    handleNode(key, value) {
        let tagName = key.split("_")[0];
        if (tagName === "") return;

        Cache.DOMCache.roundCounter[Cache.DOMCache.counter] = "node";

        let newNode = document.createElement(tagName);

        if (typeof value === "string") {
            newNode.appendChild(document.createTextNode(value));
        }

        this.appendNode(newNode);

        if (typeof value === "object" && value !== null) {
            new Content(value, newNode, this.filePath);
        }
    }

    handleTemplate(key, templateDef) {
        const cachedData = Cache.TemplateCache?.currentValues?.[key];
        if (cachedData === undefined) return;

        const type = templateDef["_type"];

        if (Array.isArray(cachedData) && type === "HTMLCollection") {
            this.renderTemplateCollection(cachedData, templateDef);
        } else if (typeof cachedData === "string") {
            this.renderTemplateString(cachedData, templateDef);
        } else if (typeof cachedData === "object") {
            this.renderTemplateGroup(cachedData, templateDef);
        } else if (type === "_callback" && typeof templateDef["_callback"] === "function") {
            templateDef["_callback"](Cache.TemplateCache.currentValues);
        }
    }

    renderTemplateCollection(dataList, templateDef) {
        const parent = this.dom.parentElement;
        const tagName = this.dom.nodeName;

        dataList.forEach((dataItem, index) => {
            let targetNode = (index === 0) ? this.dom : document.createElement(tagName);
            if(index !== 0) parent.appendChild(targetNode);

            this.processCollectionItem(targetNode, dataItem);
        });
    }

    processCollectionItem(node, dataItem) {
        const childrenToProcess = [];
        for (let key in dataItem) {
            if (!dataItem.hasOwnProperty(key)) continue;
            if (key === "_attributes") {
                const attrs = dataItem[key];
                for (let attr in attrs) {
                    if (attr === "text") {
                        node.appendChild(document.createTextNode(attrs[attr]));
                    } else {
                        const finalAttr = attr.startsWith("data_") ? attr.replace('_', '-') : attr;
                        node.setAttribute(finalAttr, attrs[attr]);
                    }
                }
            } else {
                childrenToProcess.push({ [key]: dataItem[key] });
            }
        }
        childrenToProcess.forEach(child => new Content(child, node, this.filePath));
    }

    renderTemplateString(dataString, templateDef) {
        const type = templateDef["_type"];
        const valueRef = templateDef["_value"];

        if (type === "_attributes") {
            for (let attrKey in valueRef) {
                const finalKey = attrKey.startsWith("data_") ? attrKey.replace('_', '-') : attrKey;
                this.dom.setAttribute(finalKey, dataString);
            }
        } else if (type === "text") {
            this.dom.appendChild(document.createTextNode(dataString));
        }
    }

    renderTemplateGroup(dataObject, templateDef) {
        for (let groupKey in templateDef) {
            if (!templateDef.hasOwnProperty(groupKey)) continue;
            const subDef = templateDef[groupKey];
            const type = subDef?.["_type"];
            const valueRef = subDef?.["_value"];
            const contentValue = dataObject[groupKey] || "";

            if (type === "text") {
                this.dom.appendChild(document.createTextNode(contentValue));
            } else if (type === "_attributes") {
                 for (let attrKey in valueRef) {
                     const finalAttr = attrKey.startsWith("data_") ? attrKey.replace('_', '-') : attrKey;
                     this.dom.setAttribute(finalAttr, contentValue);
                 }
            }
        }
    }

    appendNode(node) {
        if (this.after) {
            this.after.after(node);
        } else {
            this.dom.appendChild(node);
        }
    }
}

export {Partarum, Cache, Workshop, HTML, Content};