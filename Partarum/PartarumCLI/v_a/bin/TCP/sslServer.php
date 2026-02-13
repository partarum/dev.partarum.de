<?php

use Workerman\Worker;
require_once '../../../vendor/workerman/workerman/Autoloader.php';

$context = [
    "ssl" => [
        "local_cert" => "",
        "local_pk" => "",
        "verify_peer" => false
    ]
];

// Creae A Worker and listen 2347 port，not specified protocol
$tcp_worker = new Worker("ssl://0.0.0.0:40880", $context);

$tcp_worker->transport = "ssl";
// 4 processes
$tcp_worker->count = 4;

// Emitted when new connection come
$tcp_worker->onConnect = function($connection)
{
    echo "New connection\n";

    print_r($connection);
};

// Emitted when data is received
$tcp_worker->onMessage = function($connection, $data)
{
    // Send hello $data
    $file = fopen("file", "c+b");
    fwrite($file, $data);
    fclose($file);
    print_r($data);

    echo PHP_EOL;
    echo (filesize("file")) . PHP_EOL;

    $connection->send('File is transferred to ' . filesize("file"));
};

// Emitted when connection closed
$tcp_worker->onClose = function($connection)
{
    echo "Connection closed\n";
};

// Run worker
Worker::runAll();