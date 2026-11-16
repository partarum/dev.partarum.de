<?php

error_reporting(-1);
ini_set("display_errors", "1");

require_once dirname(__DIR__, 2) . "/vendor/autoload.php";

use Partarum\PartarumPHP\Document\HTMLPlain;

$html = new HTMLPlain();
$html->setScript("testClientSettingClass.js");
$html->close();