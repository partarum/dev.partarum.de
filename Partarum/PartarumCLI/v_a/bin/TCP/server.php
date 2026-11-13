<?php

require "../setRoot.php";

use Partarum\PartarumCLI\v_a\System\Root;
use Partarum\PartarumCLI\v_a\TCP\WebSocket;

require Root::$DOCUMENT_ROOT . "Partarum/PartarumCLI/v_a/TCP/WebSocket.php";

switch ($_SERVER["argv"][1]) {

    case "start":

        echo "start";
        break;

    case "status":

        echo "status";
        break;

    case "stop":

        echo "stop";
        break;
}

$websocket = new WebSocket();
$websocket->createServer(40880);
$websocket->start();