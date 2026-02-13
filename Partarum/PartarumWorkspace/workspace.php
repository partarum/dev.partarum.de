<?php
/*
 *   Copyright 2018- 2021 © Alexander Bombis. All rights reserved.
 *            Developed by Alexander Bombis.
 *            Email: email@alexander-bombis.de
 */
error_reporting(-1);
ini_set("display_errors", "1");

$option = [
  "manifest" => [

  ]
];

$proxy = new Partarum("Partarum/Workspace",Partarum::PROXY, ["proxyPath" => __DIR__]);

$proxy->addRouter("Partarum/Workspace", [
    "Partarum/PartarumWorkspace/config/routes-manifest.json",
    "Partarum/PartarumWorkspace/config/surface-manifest.json"
]);


$proxy->goDev()->with($_SERVER["REQUEST_URI"]);