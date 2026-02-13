import { ServerWorker } from "./ServerWorker.js";

const worker = new ServerWorker("KVH");

worker.defineHandler({

});

await worker.run();

self.onmessage = worker.onmessage;