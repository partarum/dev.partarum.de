import {Application} from "https://deno.land/x/oak@v12.1.0/mod.ts";

const app = new Application();

let dash = await fetch("https://cs23-0089-gx.vweb01.cordes-hosting.net/dashboardPage");

let html = await dash.text();

app.use(async (ctx) => {

    console.log(`HTTP ${ctx.request.method} on ${ctx.request.url}`);

    ctx.response.body = html;
    ctx.response.type = "text";



});

await app.listen({ port: 50202 });