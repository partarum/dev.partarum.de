<?php
error_reporting(-1);
ini_set("display_errors", "1");

require_once dirname(__DIR__, 2) . "/vendor/autoload.php";

use Partarum\PartarumPHP\Document\HTMLPage;

$html = new HTMLPage();
$html->openWithWrapper();
$html->setTitle("eine TestSeite");
$html->close();

//echo "<pre>";
//print_r($html);