import {HTMLPartarumElement} from "../HTMLPartarumElement.js";

class HTMLCookieMain extends HTMLPartarumElement {

    constructor(config){
        super(config, "main");
    }
}

(customElements.get("partarum-cookie-main") === undefined) && customElements.define("partarum-cookie-main", HTMLCookieMain);

export {HTMLCookieMain};