<?php

include $_SERVER["DOCUMENT_ROOT"] . "/Partarum/PartarumPHP/System/Autoloader.php";

use Partarum\PartarumPHP\System\Autoloader;

spl_autoload_register([Autoloader::class, "autoload"]);