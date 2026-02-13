import { ServerWorker } from "./ServerWorker.js";

const worker = new ServerWorker("index");

worker.defineHandler({

});

await worker.run();

self.onmessage = worker.onmessage;