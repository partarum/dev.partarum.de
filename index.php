<?php
/*
 *   Copyright 2018- 2021 © Alexander Bombis. All rights reserved.
 *            Developed by Alexander Bombis.
 *            Email: email@alexander-bombis.de
 */

error_reporting(-1);
ini_set("display_errors", "1");

require $_SERVER["DOCUMENT_ROOT"] . "/Partarum/Partarum.php";

use Partarum\System\Autoloader;
use Partarum\HTTP\API;

//***********************
/*
 *  Eine Funktion schreiben, welche überprüft, wie weit runter der User gehen darf, und auf welche Ordner er Zugriff hat
 *  Das wird einmal ausgeführt und dann in Redis gespeichert
 */
$nowDir = getcwd();
function goDown(): bool
{

    $dir = false;

    try {
        $dir = @chdir("..");
    } catch( Exception $err) {

    }

    return $dir;
}

$scanArray = [[]];

$stopArea = [
  "bin",
  "boot",
  "dev",
  "etc"
];

while(goDown() === true) {

    $scan = scandir(getcwd());

    if(array_intersect($stopArea, $scan) === $stopArea ){
        break;
    }

    if($scanArray[array_key_last($scanArray)] !== $scan){
        $scanArray[] = $scan;
    } else {
        break;
    }
}

chdir($nowDir);

//***********************

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

    //echo $uri;

    $app = new Partarum("cs23-0089-gx_new");

    // TODO: Unterscheidung zwischen goDev() und goPublic()

    $app->addAutoloader(["app" => ROOT . "app"], Autoloader::FOLDER);

    $app->goDev()->with($uri);
}

function jsPage($uri): void{

    $headers = getallheaders();

    $url = "http://localhost:50201" . $uri;
    $data = [
        "token" => "a1b2c3",
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

//$token = $_GET['token']  ?? "123456";


//if((isset($token)) && ($token === "123456")) {

// TODO: In Partarum die Prüfung auf Querys durchführen

$uri = (!str_contains($_SERVER["REQUEST_URI"], "?")) ? $_SERVER["REQUEST_URI"] : $_SERVER["REDIRECT_URL"]; // $_SERVER["REDIRECT_URL"] ist ohne Query

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
//}