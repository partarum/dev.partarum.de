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

?>
<!DOCTYPE html>
<html lang="de">

    <head>
        <meta charset="UTF-8">
        <title>Dr. Franz-Josef Lückge Consulting</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

        <link rel="stylesheet" type="text/css" href="/public/assets/css_backup/dashboard.css" media="all">

        <link rel="stylesheet" href="/public/assets/fonts/fontawesome/css/all.css">
    </head>
    <body class="prt-area">
        <header id="header" class="prt-a1 prt-cont"></header>
        <main id="content" class="prt-a2 prt-cont"></main>
        <footer id="footer" class="prt-a3 prt-cont"></footer>
        <script type="module" crossorigin="use-credentials" src="/public/assets/js/dashboardPage.js"></script>
    </body>
</html>