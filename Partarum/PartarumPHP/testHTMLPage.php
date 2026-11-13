<?php
error_reporting(-1);
ini_set("display_errors", "1");

include __DIR__ . "/System/Autoloader.php";

use Partarum\PartarumPHP\System\Autoloader;

spl_autoload_register([Autoloader::class, "autoload"]);

use Partarum\PartarumPHP\Document\HTMLPage;

$html = new HTMLPage();
$html->openWithWrapper();
$html->setTitle("eine TestSeite");
$html->close();

//echo "<pre>";
//print_r($html);