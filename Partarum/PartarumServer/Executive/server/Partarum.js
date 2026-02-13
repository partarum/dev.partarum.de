import {Application} from "https://deno.land/x/oak@v12.1.0/mod.ts";

const app = new Application();

app.use(async (ctx) => {

    console.log(`HTTP ${ctx.request.method} on ${ctx.request.url}`);

    ctx.response.body = "testRoute ---------Partarum--------- lalalal";
});

await app.listen({ port: 59999 });