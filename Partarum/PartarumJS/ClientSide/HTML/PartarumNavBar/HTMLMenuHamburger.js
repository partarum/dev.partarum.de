import {HTMLPartarumHost} from "../HTMLPartarumHost.js";

class HTMLMenuHamburger extends HTMLPartarumHost {

    /*
           ! max-width 1100px

     */

    constructor(config){
        super(config, "partarum-menu-hamburger", "partarumMenuHamburger");

        this.loadStyle({

            link: "/Partarum/PartarumCSS/PartarumElements/menuHamburger.css",
            style: document.querySelectorAll('style[id*="fa"]')

        }).then(() => {

        });

        this.root.dom.add("input", document.createElement("input"), null);
        this.root.dom.get("input").setAttribute("id", "partarumMenuHamburgerTrigger");
        this.root.dom.get("input").setAttribute("type", "checkbox");
        this.root.dom.get("input").classList.add("hamburger");

        this.root.dom.add("shadowBox", this.root.dom.get("input"), "append");

        if(Reflect.has(config, "parent")){

            if((config?.position === "left") || (config?.position === "center")){

                config.parent.before(this);
            } else {

                config.parent.after(this);
            }

        }
    }

    connectedCallback(){
        super.connectedCallback();

        this.id = "partarumMenuHamburger";
    }
}

(customElements.get("partarum-menu-hamburger") === undefined) && customElements.define("partarum-menu-hamburger",HTMLMenuHamburger);

export {HTMLMenuHamburger};