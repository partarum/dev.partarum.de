<?php
require "../System/Root.php";

use Partarum\PartarumCLI\v_b\System\Root;

Root::setRootObject();

chdir(Root::$DOCUMENT_ROOT);

require "Partarum/PartarumPHP/Filesystem/Filesystem.php";

use Partarum\PartarumPHP\Filesystem\Filesystem;

$fs = new Filesystem("Partarum/PartarumJS", Filesystem::RECURSIVE);

//var_dump($fs::$fileFolder);

file_put_contents("partarum-manifest-paths.json", json_encode($fs->getAllIterator(), JSON_PRETTY_PRINT));