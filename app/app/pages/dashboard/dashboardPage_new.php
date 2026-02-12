<?php
use Partarum\HTTP\API;
use Partarum\Security\Token\JWT;

session_start();

$api = new API();

$domain = $_ENV["DOMAIN"] ?? $_SERVER["SERVER_NAME"];

if((isset($_SESSION["arrival"])) && (isset($_SESSION["bearer"]))){

    if(isset($_COOKIE["bearer"])){

        $ar = (string) $_SESSION["arrival"];
        $bear = (string) $_COOKIE["bearer"];

        if($ar === $bear){

            setcookie("arrival", time(), [
                "expires" => time() + 3600,
                "path" => "/dashboardPage",
                "domain" => $domain,
                "secure" => true,
                "httponly" => true,
                "samesite" => "strict"
            ]);

        } else {
            $api->redirectInside("/loginPage");
        }
    } else {
        $api->redirectInside("/loginPage");
    }

} else {
    $api->redirectInside("/loginPage");
}

$backend = new Partarum("testDB");

$db = $backend->createDBConnection();

?>
<!DOCTYPE html>
<html lang="de">

    <head>
        <meta charset="UTF-8">
        <title>Dr. Franz-Josef Lückge Consulting</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

        <link rel="modulepreload" href="/public/assets/js/landingPage.js" as="script" type="text/javascript">

        <link rel="stylesheet" href="/public/assets/fonts/fontawesome-subset/css/all.min.css">

        <style>

            :host, :root {
                --icon-cookie: "\f8fb";
            }

            }

        </style>

        <!--    <link rel="stylesheet" type="text/css" href="/Partarum/PartarumCSS/partarum.css" media="all">-->

        <link rel="stylesheet" type="text/css" href="/public/assets/css_backup/dashboard_new.css" media="all">
        <link rel="stylesheet" type="text/css" href="/public/assets/css_backup/landingPage_new.css" media="all">
    </head>
    <body class="prt-area dashboard">

    <header id="header" class="prt-area__header prt-to-navigation dashboard-header">
        <nav class="prt-nav-header dashboard-header__navigation navigation">
            <ul class="navigation__list">
                <li class="navigation__item">
                    <i class="fa-sharp-duotone fa-regular fa-grid-2-plus"></i>
                </li>
            </ul>
        </nav>
    </header>
    <main id="content" class="prt-area__main prt-to-section">
        <section class="prt-section prt-sec1 prt-to-section prt-to-toolbox">
            <div role="toolbar" class="prt-toolbox prt-tlb1">
                <p>1</p>
            </div>
            <div class="prt-section">
               <menu>
                   <li>
                       <button class="">Januar</button>
                   </li>
                   <li>
                       <button class="">Februar</button>
                   </li>
                   <li>
                       <button class="">März</button>
                   </li>
                   <li>
                       <button class="">April</button>
                   </li>
                   <li>
                       <button class="">Mai</button>
                   </li>
                   <li>
                       <button class="">Juni</button>
                   </li>
                   <li>
                       <button class="">Juli</button>
                   </li>
                   <li>
                       <button class="">August</button>
                   </li>
                   <li>
                       <button class="">September</button>
                   </li>
                   <li>
                       <button class="">Oktober</button>
                   </li>
                   <li>
                       <button class="">November</button>
                   </li>
                   <li>
                       <button class="">Dezember</button>
                   </li>
               </menu>
            </div>
            <div role="toolbar" class="prt-toolbox prt-tlb2">
                <p>2</p>
            </div>
        </section>
        <section class="prt-section prt-sec2 prt-to-section">
            <section class="prt-section prt-sec1 prt-to-header prt-to-article prt-to-toolbox">
                <header class="prt-header">
                    <h2>Input</h2>
                </header>
                <article class="prt-article"></article>
                <div role="toolbar" class="prt-toolbox"></div>
            </section>
            <section class="prt-section prt-sec2 prt-to-header prt-to-article prt-to-toolbox">
                <header class="prt-header">
                    <h2>Output</h2>
                </header>
                <article class="prt-article"></article>
                <div role="toolbar" class="prt-toolbox"></div>
            </section>
        </section>
    </main>
    <footer id="footer" class="prt-area-footer prt-to-navigation">
        <nav class="prt-nav-footer"></nav>
    </footer>


    <!--<script type="module" crossorigin="use-credentials" src="/public/assets/js/landingPage.js"></script>-->
    </body>
    <!--
    <body class="prt-area">
        <header id="header" class="prt-a1 prt-cont"></header>
        <main id="content" class="prt-a2 prt-cont"></main>
        <footer id="footer" class="prt-a3 prt-cont"></footer>
        <script type="module" crossorigin="use-credentials" src="/public/assets/js/dashboardPage.js"></script>
    </body>
    -->
</html>