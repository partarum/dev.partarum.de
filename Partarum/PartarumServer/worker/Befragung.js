import { ServerWorker } from "./ServerWorker.js";

const worker = new ServerWorker("Befragung");

worker.defineHandler({

});

await worker.run();

self.onmessage = worker.onmessage;