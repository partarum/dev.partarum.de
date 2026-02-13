class ServerWorker {

    name;
    defaultHandler = {};

    screenCounter = 0;

    screenID = "";

    status = {
        startScreen: false,
        startServer: false
    }

    constructor(name) {
        this.name = name;
    }

    defineHandler(handler) {
        this.defaultHandler = handler;
    }

    onmessage(ev) {
        const getObject = new Promise((resolve) => {
            const messageObject = {
                data: this.name,
                name: self.name
            }

            //console.dir(ev.data);

            switch (ev.data.data) {
                case "Stop":
                    messageObject.data = "Server is stopped"
                    resolve(messageObject);
                    break;
                case "Start":
                    messageObject.data = "Server is started"
                    resolve(messageObject);

                    break;
                case "Restart":

                    break;
                default:
                    messageObject.data = "Problem?";
                    resolve(messageObject);
            }
        });


        getObject.then((data) => {

            //console.dir(data);
            self.postMessage(data);
        });
    }

    checkStatus () {

        if((this.status.startServer === false) && (this.status.startScreen === false)){
            return false;
        }
    }

    setScreenID () {
        return new Promise((resolve, reject) => {

            if(this.screenID === "") {
                this.screenID = "server_" + this.name + "_" + this.screenCounter;

                resolve(this.screenID);
            } else {
                reject(false);
            }
        });
    }

    setScreen() {

        return new Promise((resolve, reject) => {
            const p = Deno.run({
                cmd: [
                    "screen",
                    "-dmS",
                    this.screenID
                ]
            });

            p.status().then((res) => {

                console.dir(p);

                if(res.success === true){

                    this.status.startScreen = true;
                    resolve(true);
                }
            });

        })
    }

    getScreenList() {

        return new Promise((resolve, reject) => {
            const p1 = Deno.run({
                cmd: [
                    "screen",
                    "-list"
                ]
                //  stdout: "piped"
            });

            p1.status().then((res) => {

                console.dir(p1);
                (res.success === true) && resolve(true);
            });

        });
    }

    setServer() {
        return new Promise((resolve, reject) => {

            const p2 = Deno.run({
                cmd: [
                    "screen",
                    "-S",
                    this.screenID,
                    "-X",
                    "stuff",
                    "deno run --allow-net --allow-run --allow-read --allow-write ./Partarum/PartarumServer/server/" + this.name + ".js \n",
                ]
            });

            p2.status().then((res) => {

                console.dir(p2);
                if(res.success === true) {

                    this.status.startServer = true;
                    resolve(true);
                }
            });
        });
    }

    killAll() {
        // killall screen; screen -wipe
    }

    async run() {

        this.setScreenID().then(async (res) => {
            await this.setScreen();
            await this.getScreenList();
            await this.setServer();
        });
    }
}

export { ServerWorker };

/*
p1.output().then( (res) => {
    console.dir(res);
});

 */