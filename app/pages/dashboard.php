<?php

error_reporting(-1);
ini_set("display_errors", "1");

use Partarum\HTTP\API;
use Partarum\Security\Token\JWT;

$api = new API();

$user = $api->fromPOST("user") ?? NULL;

$password = $api->fromPOST("password") ?? NULL;

// ! Nächstes Problem -> unter Localhost können natürlich keine https Weiterleitungen zu http gemacht werden

// TODO: Einbinden der Verarbeitung von $_SERVER["REQUEST_SCHEME"]; - ist aber erst ab Apache 2.4 von grund auf dabei

// TODO: Password hashen  - sha256
//$hashedPW =

/*
 *  Es gibt drei Möglichkeiten
 *
 *  1. Die Domain von $_SERVER
 *  2. Die $_ENV["DOMAIN_PROD"]
 *  3. Die $_ENV["DOMAIN_DEV"]
 *  4. Die $_ENV["DOMAIN_TEST"]
 *
 *  a: (1 === 2) && (1 !== 3) && (1 !== 4)
 *  b: (1 !== 2) && (1 === 3) && (1 !== 4)
 *  c: (1 !== 2) && (1 !== 3) && (1 === 4)
 *  d: (1 !== 2) && (1 !== 3) && (1 !== 4)
 *
 *  wenn a, dann hat 2 vorrang
 *
 *  Was will ich erreichen?
 *
 *  Das anhand der Vorgaben automatisch die richtige Domain gewählt wird
 *
 *
 */


$domain = $_ENV["DOMAIN"] ?? $_SERVER["SERVER_NAME"];

echo $domain;

$jwt = new JWT();

if(isset($_COOKIE["beer"])){

    echo "beer";

    if((isset($user)) && (isset($password))){

        if(($user === $_ENV["AUTH_USER"]) && ($password === $_ENV["AUTH_PASSWORD"])){

            $jwt->setSecretKey($_ENV["JWT_SECRET_KEY"]);

            define("TIME_NOW", time());

            $jwt->setPayload([
                "user" => $user,
                "time" => TIME_NOW
            ]);

            $bearer = $jwt->getToken();

            session_start();

            $_SESSION["bearer"] = $bearer;
            $_SESSION["arrival"] = TIME_NOW;

            setcookie("bearer", TIME_NOW, time() + 120, "/dashboardPage", $domain, true, true);

            $api->redirectInside("/dashboardPage");
        } else {

            $log = [
                "cwd" => getcwd()
            ];

            $filename = date("Y-m-d"). "_" . time() . ".json";

            (!is_dir("app/log")) && mkdir("app/log");

            (!is_dir("app/log/loginPage")) && mkdir("app/log/loginPage");

            //file_put_contents("app/log/loginPage/${filename}", json_encode($log));

            $api->redirectInside("/loginPage");
        }

    } else {

        $api->redirectInside("/loginPage");
    }
} else {
    $api->redirectInside("/loginPage");
}