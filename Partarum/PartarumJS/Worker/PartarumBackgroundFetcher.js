let directory = {};

self.onmessage = (event) => {

    const message = event.data.message;

    const expires = event.data.expires;

    const option = event.data.option;

    const requestType = event.data.requestType;

    const responseType = event.data.responseType;

    const url = location.origin;

    if(requestType === "init"){

        directory = Object.assign(directory, message);
    }

    if(requestType === "form") {

        let body = option.body;

        let formBody = new FormData();

        for(const key in body){

            formBody.append(key, body[key]);
        }

        option.body = formBody;
    }

    const action = directory?.[message];

    if((action !== null) && (requestType !== "init")) {

        fetch(action, option).then((res) => {

            let data;

            switch(responseType) {

                case "json":
                    data =  res.json();
                    break;
                case "text":
                    data = res.text();
                    break;
                case "blob":
                    data = res.blob();
                    break;
                case "buffer":
                    data = res.arrayBuffer()
            }

            return data;

        }).then((data) => {

            self.postMessage(data);
        });
    } else {

        self.postMessage(null);
    }
};