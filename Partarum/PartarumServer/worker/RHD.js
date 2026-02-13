import { ServerWorker } from "./ServerWorker.js";

const worker = new ServerWorker("RHD");

worker.defineHandler({

});

await worker.run();

self.onmessage = worker.onmessage;