import {Semaphore} from "https://deno.land/x/async@v1.1.5/mod.ts";
import {delay} from "https://deno.land/std@0.86.0/async/mod.ts";
import {ServiceWorker} from "./ServiceWorker.js"
import {FlagWorker} from "./FlagWorker.js";

class Service {
    sem;

    workerCache = [];

    workerGarbageCollector = new WeakMap();

    pathCache = new Map();

    nameCache = [];

    genID;

    flagStatus = new FlagWorker();

    flags = {
        construct: 0x0001,
        add: 0x0002,
        run: 0x0004
    }

    constructor(options = {
        count: 1
    }) {

        this.genID = this.getID(options.count);

        this.sem = new Semaphore(options.count);

        this.flagStatus.add(this.flags.construct);
    }

    static init(options) {

        return new Service(options);
    }

    setOptions(options){
        this.genID = this.getID(options.count);

        this.sem = new Semaphore(options.count);
    }


    * getID(num) {

        for(let i=0; i < num; i++) {
            yield i;
        }
    }

    add(name, workerURL) {

        return new Promise((resolve, reject) => {

            if(this.sem.locked() === false) {

                this.flagStatus.add(this.flags.add);

                this.sem.acquire().then(() => {

                    const worker = new ServiceWorker("worker/" + workerURL);

                    const idNum = this.genID.next().value;

                    const id = "server_" + idNum;

                    this.pathCache.set(id, name);

                    this.nameCache.push(name);

                    this.workerCache[idNum] = worker.init();

                    this.workerGarbageCollector.set(worker, name)

                    localStorage.setItem(name, JSON.stringify({
                        idNum: idNum,
                        id: id
                    }));

                    resolve(id);
                })
            } else {
                reject(null);
            }
        });
    }

    get(name) {

        return new Promise((resolve, reject) => {

            let idObject = JSON.parse(localStorage.getItem(name));

            let id = idObject.id;

            let idNum = idObject.idNum;

            resolve(this.workerCache[idNum]);

        });
    }

    getAll() {

        return new Promise((resolve, reject) => {

            this.status().then((status) => {
                console.dir("getAll - status: " + status);

                if(status === 4){
                    resolve(this.workerCache);
                }
            });

            this.status(4).then((status) => {

                console.dir("getAll - in Promise - status: " + status);
                resolve(this.workerCache);
            });
        });
    }

    remove() {

    }

    * getWorker() {

        for(let worker of this.workerCache) {

            yield worker;
        }
    }

    run() {
        return new Promise((resolve, reject) => {

            Promise.all(this.workerCache).then((workers) => {
                this.flagStatus.add(this.flags.run);
                resolve(workers);
            });
        });
    }

    status(num = 0){
        return new Promise((resolve, reject) => {

            if(num === 0) {

                if (this.flagStatus.has(this.flags.run)) {
                    resolve(4);
                } else if (this.flagStatus.has(this.flags.add)) {
                    resolve(2);
                } else if (this.flagStatus.has(this.flags.construct)) {
                    resolve(1);
                } else {
                    resolve(0);
                }
            } else {
                // TODO: check if the status === num
                this.flagStatus.status(num).then((res) => {
                    resolve(res);
                });
            }
        })
    }
}

export {Service};