self.onmessage = (event) => {

    let message = event.data.message;

    let expires = event.data.expires;

    let action = location.origin + "/Partarum/PartarumAPI/V1/Purity/";

    switch (message) {

        case "getBeer":
        case "holdBeer":
            action += "holdBeer";
            break;
        case "tasteBeer":
            action += "tasteBeer";
            break;
        case "drinkBeer":
            action += "drinkBeer";
    }

    fetch(action, {
        method: "POST",
        body: (() => {
            let f = new FormData();
            f.append("beer", "beer");
            f.append("expires", expires);
            return f;
        })()
    }).then((res) => {
        return res.json();
    }).then((json) => {

        if((Reflect.has(json[0], "post")) && (Reflect.has(json[0], "token"))) {

            if(Reflect.has(json[0].token, json[0].post)) {

                self.postMessage([json[0].post, json[0].token.beer]);

            }
        }
    });
};