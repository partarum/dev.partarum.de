/*
 *   Copyright 2018- 2021 © Alexander Bombis. All rights reserved.
 *            Developed by Alexander Bombis.
 *            Email: email@alexander-bombis.de
 */

import {Log} from "../Workshop/Log.js";
import {Validation} from "../Validation/Validation.js";
import {ValidObject} from "../Validation/ValidObject.js";
import {Helper} from "../Workshop/Helper.js";
import {ViewKit} from "../Workshop/ViewKit.js";
import {Loader} from "../Workshop/Loader.js";
import {Workbox} from "../Workshop/Workbox.js";
import {Cache} from "../Cache/Cache.js";
import {HTML} from "../HTML/HTML.js";
import {Design} from "../Design/Design.js";
import {Counter} from "../Helper/Counter.js";
import {System} from "../System/System.js";
import {Draw} from "../Draw/Draw.js";
import {Events} from "../Events/Events.js";

const ImportList = {
    Log: Log,
    Validation: Validation,
    ValidObject: ValidObject,
    Helper: Helper,
    ViewKit: ViewKit,
    Loader: Loader,
    Workbox: Workbox,
    Cache: Cache,
    HTML: HTML,
    Design: Design,
    Counter: Counter,
    System: System,
    Draw: Draw,
    Events: Events
}

export {ImportList};