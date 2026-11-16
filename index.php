<?php
/*
 *   Copyright 2018- 2021 © Alexander Bombis. All rights reserved.
 *            Developed by Alexander Bombis.
 *            Email: email@alexander-bombis.de
 */

error_reporting(-1);
ini_set("display_errors", "1");

require __DIR__ . "/Partarum/Partarum.php";

use Partarum\Partarum;
use Partarum\PartarumPHP\HTTP\API;

/*
 *  ! Routing an die Anforderung
 *      + statisch HTML
 *      + dynamisch PHP
 *      + JavaScript - Server
 *  anpassen !
 */

function apiResponse(): void{
    $api = new API();

    $request = $api->createRequest("http://localhost:50201");

    print_r($request);
}

function phpPage($uri): void{

    $app = new Partarum("cs23-0089-gx_new");

    // TODO: Unterscheidung zwischen goDev() und goPublic()

    $app->goDev()->with($uri);
}

function jsPage($uri): void{

    $headers = function_exists("getallheaders") ? getallheaders() : [];

    $url = ($_ENV["JS_SERVER_URL"] ?? "http://localhost:50201") . $uri;
    $data = [
        "token" => $_ENV["JS_SERVER_TOKEN"] ?? $_ENV["TOKEN"] ?? "",
        "post" => $_POST,
        "get" => $_GET,
        "headers" => $headers
    ];

    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_POST => count($data),
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_RETURNTRANSFER => true
    );

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);

    curl_close($ch);

    echo $response;
}

// TODO: In Partarum die Prüfung auf Querys durchführen

$uri = (!str_contains($_SERVER["REQUEST_URI"], "?")) ? $_SERVER["REQUEST_URI"] : ($_SERVER["REDIRECT_URL"] ?? parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));

switch ($uri) {
    case "/testDashboard":
    case "/testBefragung":
        jsPage($uri);
        break;
    case "/server":
        echo "<pre>";
        print_r($_SERVER);
        break;
    default:
        phpPage($uri);
}