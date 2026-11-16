<?php
declare(strict_types=1);

error_reporting(-1);
ini_set("display_errors", "1");

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";

use Partarum\PartarumPHP\Document\HTMLDocument;
use Partarum\PartarumPHP\Document\HTMLElement;
use Partarum\PartarumPHP\Document\HTMLFamily;
use Partarum\PartarumPHP\Document\HTMLSeries;

$html = new HTMLDocument();

$wrapper = new HTMLElement("div", $html->body);
$wrapper->id = "wrapper";
$wrapper->text = "Ich bin der Wrapper!!!";
$html->wrapper = $wrapper;

$head_0 = new HTMLElement("header", $wrapper);
$head_0->id = "head_0";
$html->head_0 = $head_0;

$head_0_Section = new HTMLSeries("section", $html->head_0);
$head_0_Section->quantity = 2;
$head_0_Section->id = ["head_0_TitleBox", "head_0_NavBox"];

$nav = new HTMLFamily("nav", $head_0->children[1]);
$nav->id = "mainNavigation";
$nav->setChildren("ul", 1);
$nav->children[0]->id = "navUL";
$nav->setChild("ul");
$nav->setGrandChildren("li", 0, 5);
$nav->setGrandChild("li", 0);

$html->set();
