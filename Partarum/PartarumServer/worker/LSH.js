import { ServerWorker } from "./ServerWorker.js";

const worker = new ServerWorker("LSH");

worker.defineHandler({

});

await worker.run();

self.onmessage = worker.onmessage;