import {HTMLPartarumElement} from "../HTMLPartarumElement.js";

class HTMLCardBoxHeader extends HTMLPartarumElement {

    constructor(config, id = null){
        super(config, "header");

        this.id = id || "";

        //this.className = "box-row box-center";
    }

    /*
        ! Hier die jeweiligen Menus und Canvas erstellen
     */

    addTopic(topic) {

        super.addTopic(topic, "button");
    }

    addMenu(){

    }

    addCanvas(){
        
    }
}

(customElements.get("partarum-card-box-header") === undefined) && customElements.define("partarum-card-box-header", HTMLCardBoxHeader);

export {HTMLCardBoxHeader};