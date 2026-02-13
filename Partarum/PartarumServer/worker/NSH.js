import { ServerWorker } from "./ServerWorker.js";

const worker = new ServerWorker("NSH");

worker.defineHandler({

});

await worker.run();

self.onmessage = worker.onmessage;