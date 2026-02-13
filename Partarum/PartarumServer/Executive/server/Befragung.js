import {Application} from "https://deno.land/x/oak@v12.1.0/mod.ts";

const app = new Application();

const html = await Deno.readTextFile("app/Befragung.html");

app.use(async (ctx) => {

    console.log(`HTTP ${ctx.request.method} on ${ctx.request.url}`);

    ctx.response.body = html;
    ctx.response.type = "text";
});

await app.listen({ port: 50203 });