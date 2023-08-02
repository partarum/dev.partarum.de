import {CacheStorage} from "./CacheStorage/CacheStorage.js";
import {PartarumCache} from "./PartarumCache.js";
import {WorkingCache} from "./WorkingCache.js"
import {ImportCache} from "./ImportCache.js";
import {HTMLCache} from "./HTMLCache.js";
import {DOMCache} from "./DOMCache.js";
import {EventCache} from "./EventCache.js";
import {CounterCache} from "./CounterCache.js";
import {SimpleCache} from "./SimpleCache.js";
import {IndexCache} from "./IndexCache.js";
import {PlotCache} from "./PlotCache.js";


class Cache {

    static IndexCache = IndexCache;

    static PartarumCache = PartarumCache;

    static WorkingCache = WorkingCache;

    static ImportCache = ImportCache;

    static DOMCache = DOMCache;

    static EventCache = EventCache;

    static CounterCache = CounterCache;

    static SimpleCache = SimpleCache;

    static CacheStorage = new CacheStorage();

    static HTMLCache = HTMLCache;

    static PlotCache = PlotCache;

}

export {Cache};