import {HTMLCardBox} from "./HTMLCardBox.js";
import {HTMLNavBar} from "./HTMLNavBar.js";
import {HTMLBottomBar} from "./HTMLBottomBar.js";
import {HTMLCookie} from "./HTMLCookie.js";
import {HTMLRegistrationBox} from "./HTMLRegistrationBox.js";


class HTML {

    static BottomBar = HTMLBottomBar;

    static createBottomBar(...args) {
        return new HTMLBottomBar(...args);
    }

    static CardBox = HTMLCardBox;

    static createCardBox(...args) {
        return new HTMLCardBox(...args);
    }

    static CookieBanner = HTMLCookie;

    static createCookieBanner(...args) {
        return new HTMLCookie(...args);
    }

    static NavBar = HTMLNavBar;

    static createNavBar(...args) {
        return new HTMLNavBar(...args);
    }

    static RegistrationBox = HTMLRegistrationBox;

    static createRegistrationBox(...args) {
        return new HTMLRegistrationBox(...args);
    }


    static counter = 0;

    static confirmLink(config) {
       
        const {href,confirm,title,options} = config;

        ["href", "confirm", "title", "options"].forEach((key)=>Reflect.deleteProperty(config, key));

        this.counter++;

        return {
            href: "javascript:void(0)",
            title: title || "Link",
            addEvent: {
                type: "click",
                topic: "ConfirmLink",
                theme: "confirmLink_click",
                name: "confirmLink_click_" + this.counter,
                targetID: "confirmLink_" + this.counter,
                doThat: (ev) => {

                    Partarum.Validation.confirmLink(confirm, href, options);
                }
            },
            ...config
        };
    }

    static getSafeElementById(id, timeout = 250){

        return new Promise((resolve) => {

            Partarum.HTML.getSafeElement("byID", id, timeout).then((element) => {

                resolve(element);
            });
        });
    }

    static getSafeElementByQueryString(queryString, timeout = 250) {

        return new Promise((resolve) => {

            Partarum.HTML.getSafeElement("byQuery", queryString, timeout).then((element) => {

                resolve(element);
            });
        });
    }

    static getSafeElement(type, needle, timeout = 250) {

        return new Promise((resolve, reject) => {

            console.dir(needle);

            let element = (type === "byID") ? document.getElementById(needle) : document.querySelector(needle);

            let intID;

            let counter = 0;

            let funcBreak = () => {

                if((element === null) && (counter < 1000)){

                    element = (type === "byID") ? document.getElementById(needle) : document.querySelector(needle);

                    if(element === null){

                        counter++;

                        funcBreak();

                    } else {

                        resolve(element);
                    }

                } else {

                    clearInterval(intID);
                    resolve(element);
                }
            };

            if(element === null){

                intID = setInterval(funcBreak, timeout);

            } else {

                resolve(element);
            }
        })
    }
}

const testElement = class extends HTMLElement {

    constructor(attr) {
        super();

        console.log("testElement: ");
        console.dir(attr);
        console.dir(this);
    }
};

class testElement2 extends HTMLElement {

    constructor(attr) {
        super();

        console.log("testElement2: ");
        console.dir(attr);
        console.dir(this);
    }
}

customElements.get("test-element") || customElements.define("test-element", testElement);
customElements.get("test-element2") || customElements.define("test-element2", testElement2);

export {HTML, HTMLBottomBar, HTMLCardBox, HTMLCookie, HTMLNavBar, HTMLRegistrationBox, testElement, testElement2};