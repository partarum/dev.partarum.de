import YAML from "npm:yaml@2.2.1";
import {delay} from "https://deno.land/std@0.86.0/async/mod.ts";
import { Service } from "./Service.js";

console.dir(Deno.cwd());

const serv = new Service();

/*
    new Service()
    Service.add()
    Service.run()
    Service.status()
    Service.get()
 */

localStorage.clear();

Deno.readTextFile("config/routes-manifest.yaml").then((routeString) => {

    const routesObject = YAML.parse(routeString);

    if(Object.hasOwn(routesObject, "routes")){

        let routes = Object.keys(routesObject.routes);

        serv.setOptions({
            count: routes.length
        });

        let routCounter = 0;

        for(let route of routes){

            const server = routesObject.routes[route].server;

            const worker = routesObject.routes[route].worker;

            serv.add(server, worker).then((data) => {

                routCounter++;

                serv.status().then((data)=>{
                    console.log("serv.add - status: " + data);
                });

                if(routCounter === routes.length){

                    serv.run().then(async (data) => {

                        serv.status().then((data)=>{
                            console.log("serv.run - status: " + data);
                        });

                        for(let worker of data){

                            worker.postMessage({
                                data: "Test"
                            });

                            worker.onmessage = (ev) => {
                                console.log(ev.data);
                            }
                        }
                    });
                }
            });
        }
    }
});

serv.status(4).then((resStatus) => {

    console.dir(resStatus);

/*
    serv.getAll().then((workers) => {

        console.log("getAll workers");

        for(let worker of workers){

            worker.then((data) => {

                console.dir(data);

                data.postMessage({
                    data: "Start"
                });

                data.onmessage = (ev) => {
                    console.dir(ev.data);
                }
            });
        }
    });

 */


    serv.get("http://localhost:50202").then((worker) => {

        console.dir("get one worker");

        worker.postMessage({
            data: "test"
        });

        worker.onmessage = (ev) => {
            console.dir(ev.data);
        }
    });

});


/*
await p.status().then(() => {
    const n = Deno.run({
        cmd: ["denon", "startIndex"],
    });
});

 */

