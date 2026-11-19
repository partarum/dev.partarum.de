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

        if ((Reflect.has(json[0], "post")) && (Reflect.has(json[0], "token"))) {

            const postKey = Array.isArray(json[0].post) ? json[0].post[0] : json[0].post;

            if (postKey && Reflect.has(json[0].token, postKey)) {

                self.postMessage([postKey, json[0].token[postKey]]);

            }
        }
    });
};