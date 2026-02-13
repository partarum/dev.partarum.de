<?php

require_once "System/Executive.php";
require_once "System/Handle/ServerType.php";

use Partarum\PartarumCLI\v_b\PartarumServer\System\Executive;
use Partarum\PartarumCLI\v_b\PartarumServer\System\Handle\ServerType;

echo count($argv);

Executive::initConfig(ServerType::Websocket);