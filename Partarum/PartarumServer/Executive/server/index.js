import {Application, Router, Status} from "https://deno.land/x/oak@v12.1.0/mod.ts";
import {proxy} from "https://deno.land/x/oak_http_proxy@2.1.0/mod.ts";

const app = new Application();

const router = new Router();

const token = "a1b2c3";

let tokenStatus = false;

app.use(async (ctx, next) => {

    let tokenGet = await ctx.request.body().value;

    console.dir(ctx.request.url);

    if(token === tokenGet.get("token")) {

        tokenStatus = true;

        await next();
    } else {

        ctx.response.status = Status.Forbidden;
        ctx.response.body = "Verbindung abgelehnt";
    }

});

router.post('/dashboard', proxy("http://localhost:50202"));

router.post('/befragung', proxy("http://localhost:50203"));

app.use(router.routes());

app.addEventListener('listen', () => {
    console.log(`Listening on localhost:50201`);
});

await app.listen({ port: 50201 });