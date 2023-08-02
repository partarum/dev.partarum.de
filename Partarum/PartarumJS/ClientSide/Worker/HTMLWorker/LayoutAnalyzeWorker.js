function getBaseVertices(node){

    return new Promise((resolve) => {

        let {width, height, top, left} =  node;

        const DPR = window.devicePixelRatio || 1;

        const DPI = 96;

        resolve({
            width: width,
            height: height,
            left: left,
            right: left + width,
            top: top,
            bottom: top + height,
            leftTop: {
                x: left,
                y: top
            },
            rightTop: {
                x: left + width,
                y: top
            },
            rightBottom: {
                x: left + width,
                y: top + height
            },
            leftBottom: {
                x: left,
                y: top + height
            },
            baseUnits: {
                inch: {
                    px: 96,
                    cm: 2.54
                },
                px: {

                }
            },
            units: {
                vw: width / 100,
                vh: height / 100,
                mm: ""
                /*
                    in = inches (1in = 96px = 2.54cm)
                    px = pixels (1px = 1/96th of 1in)

                    100vh = 1289px
                    1vh = 12,89px
                    1in = 96px = 2,54cm
                    12,89px = 12,89 * 100 / 96 = 13,42% von 1in  = 0,1342in
                    1in = 2,54cm = 0,5in * 2,54cm = 1,27cm = 1,27 / 2,54 = 0,5in
                    1cm = 1 / 2,54cm = 0,393in = 0,39 * 100 = 39% von 1in = 39 * 96 / 100 = 37,44px = 1289 / 37,44 / 100 = 0,344vh

                    (644px / 1289px) * 100 = 50%

                    console.dir(window.devicePixelRatio);
                 */
            }
        });
    });
}

function getAllVertices(base, node){

    return new Promise((resolve) => {

        const OVER = 1;

        const UNDER = 2;

        let place = 0;

        if(base.top > node.top){
            // node liegt über ctx
            place = OVER;
        } else {
            // node liegt im oder unter ctx
            if(node.top > base.bottom){
                //node liegt unter ctx
                place = UNDER;
            }
        }

        let res = {
            ...node,
            nodeToBase: base,
            type: place,
            nodeSelf: {
                leftTop: {
                    x: node.left,
                    y: (place === 1) ? 0 : base.height
                },
                rightTop: {
                    x: (node.left - base.left) + node.width,
                    y: (place === 1) ? 0 : base.height
                },
                rightBottom: {
                    x: (node.left - base.left) + node.width,
                    y: (place === 2) ? base.height : 0
                },
                leftBottom: {
                    x: (node.left),
                    y: (place === 2) ? base.height : 0
                },
                centerTop: {
                    x: (node.left - base.left) + (node.width / 2),
                    y: (place === 1) ? 0 : base.height
                },
                centerBottom: {
                    x: (node.left - base.left) + (node.width / 2),
                    y: (place === 2) ? base.height : 0
                }
            }
        };

        resolve(res);
    });
}

onmessage = (message) => {

    let base  = message.data[0];

    let nodes = message.data[1];

    let id = message.data[2];


    /*
        ! Hier jetzt mit Hilfe von getAllVertex die Eckpunkte sammeln
     */

    let res = {
        base: getBaseVertices(base),
        nodes: new WeakMap()
    }

    let response = {
        id: id,
        nodes: []
    }

    res.base.then((baseData) => {

        response.base = baseData;

        let counter = 0;

        for(let node of nodes){

            getBaseVertices(node).then((baseNodeData) => {

                res.nodes.set(node, getAllVertices(baseNodeData, node));

                res.nodes.get(node).then((data) => {

                    response.nodes.push(data);

                    if(counter === nodes.length - 1){

                        console.dir(id);

                        postMessage(response);

                    } else {
                        counter++;
                    }
                })
            });
        }
    });
}