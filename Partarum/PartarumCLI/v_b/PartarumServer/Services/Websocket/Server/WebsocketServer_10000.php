<?php

require_once $_SERVER["PWD"] . "/Websocket.php";

use Partarum\PartarumCLI\v_b\PartarumServer\Services\Websocket;

    // ! Diese Datei ist jene welche im Screen läuft
    // ! Der Screen wird gestartet und diese Datei wird dann aufgerufen
/*
 *      Die Datei ist verantwortlich für die Executive des Websockets - Servers
 *
 *      1. Websocket - Instance erstellen
 *      2.
 */

$ip = $argv[1];

$port = $argv[2];

echo $port;

$server = new Websocket($ip, $port);

var_dump($server);

$server->create();

$server->start();