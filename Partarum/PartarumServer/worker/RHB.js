import { ServerWorker } from "./ServerWorker.js";

const worker = new ServerWorker("RHB");

worker.defineHandler({

});

await worker.run();

self.onmessage = worker.onmessage;