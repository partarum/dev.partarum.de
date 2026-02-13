<?php

echo __DIR__ . PHP_EOL;

require "../System/Root.php";

use PartarumCLI\v_a\System\Root;

Root::setRootObject();

echo Root::$HOME . PHP_EOL;
echo Root::$DOCUMENT_ROOT . PHP_EOL;