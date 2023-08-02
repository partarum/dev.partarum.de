import {HTMLPartarumHost} from "./HTMLPartarumHost.js";
import {HTMLNavBarBody} from "./PartarumNavBar/HTMLNavBarBody.js";

class HTMLNavBar extends HTMLPartarumHost {

    constructor(config){
        super(config, "partarum-nav-bar", "partarumNavBar");

        this.loadStyle({

            link: "/Partarum/PartarumCSS/PartarumElements/navBar.css",
            style: document.querySelectorAll('style[id*="fa"]')

        }).then(() => {

        });

        this.root.dom.add("NavBarBody", new HTMLNavBarBody(config, "body", this.root.dom), null);

        this.root.dom.get("NavBarBody").classList.add("single-box-center-large");

        this.root.dom.add("shadowBox", this.root.dom.get("NavBarBody"), "append");

        if(config !== undefined) {

            if (Reflect.has(config, "parent")) {

                config.parent.appendChild(this);
            }
        }
    }

    connectedCallback(){
        super.connectedCallback();

        this.id = "partarumNavBar";
    }
}

(customElements.get("partarum-nav-bar") === undefined) && customElements.define("partarum-nav-bar", HTMLNavBar);

export {HTMLNavBar};