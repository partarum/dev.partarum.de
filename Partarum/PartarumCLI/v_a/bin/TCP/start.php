<?php

require "../../TCP/WebSocket.php";

use Partarum\PartarumCLI\v_a\TCP\WebSocket;

$websocket = new WebSocket();
$websocket->createServer("0.0.0.0:40880");