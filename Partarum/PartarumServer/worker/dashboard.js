import { ServerWorker } from "./ServerWorker.js";

const worker = new ServerWorker("dashboard");

worker.defineHandler({

});

await worker.run();

self.onmessage = worker.onmessage;