<?php

error_reporting(-1);
ini_set("display_errors", "1");

use Partarum\PartarumPHP\System\Autoloader;

if(!class_exists(Autoloader::class)) {

    include __DIR__ . "/System/Autoloader.php";
    spl_autoload_register([Autoloader::class, "autoload"]);
}

use Partarum\PartarumPHP\Document\HTMLPlain;

$html = new HTMLPlain();
$html->setScript("testClientSettingClass.js");
$html->close();