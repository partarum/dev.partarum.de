import {HTMLPartarumElement} from "../HTMLPartarumElement.js";

class HTMLBottomBarBody extends HTMLPartarumElement {

    constructor(config, name){

        super(config, name);

        this.id = "partarumBottomBarBody";
    }
}

(customElements.get("partarum-bottom-bar-body") === undefined) && customElements.define("partarum-bottom-bar-body", HTMLBottomBarBody);

export {HTMLBottomBarBody};