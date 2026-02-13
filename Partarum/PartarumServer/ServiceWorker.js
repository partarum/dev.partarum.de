export class ServiceWorker {

    worker;

    url;

    options = {
        type: "module",
        name: ""
    };

    constructor (url) {

        this.url = url;
    }

    init() {

        return new Promise((resolve, reject) => {

            this.options.name = this.url;

            this.worker = new Worker(new URL(this.url, import.meta.url).href, this.options);
            resolve(this.worker);
        });
    }

    post(message){
        this.worker.postMessage(message);
    }
}