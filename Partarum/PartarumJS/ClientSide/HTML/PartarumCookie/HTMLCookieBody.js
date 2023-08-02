import {HTMLPartarumElement} from "../HTMLPartarumElement.js";

class HTMLCookieBody extends HTMLPartarumElement {

    constructor(config){
        super(config, "body");


    }
}

(customElements.get("partarum-cookie-body") === undefined) && customElements.define("partarum-cookie-body", HTMLCookieBody);

export {HTMLCookieBody};