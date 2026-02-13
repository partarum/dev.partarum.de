import { ServerWorker } from "./ServerWorker.js";

const worker = new ServerWorker("landingPage");

worker.defineHandler({

});

await worker.run();

self.onmessage = worker.onmessage;