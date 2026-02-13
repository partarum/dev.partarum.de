<?php

namespace Partarum\PartarumCLI\v_b\PartarumServer\System\Handle {

    require_once "ServerType.php";
    require_once "DataType.php";
    require_once "UserConfig.php";
    require_once "SystemConfig.php";

    use Partarum\PartarumCLI\v_b\PartarumServer\System\Handle\Datatype;
    use Partarum\PartarumCLI\v_b\PartarumServer\System\Handle\ServerType;
    use Partarum\PartarumCLI\v_b\PartarumServer\System\Handle\UserConfig;
    use Partarum\PartarumCLI\v_b\PartarumServer\System\Handle\SystemConfig;

    enum ConfigType: int
    {

        case User = 0x0001;
        case System = 0x0002;

        public function fromServer(ServerType $serverType): UserConfig | SystemConfig
        {
            $name = $serverType->name;

            return match($this){
                ConfigType::User => match($serverType) {
                    ServerType::Websocket => UserConfig::Websocket,
                    default => ""
                },
                ConfigType::System => match($serverType){
                    ServerType::Websocket => SystemConfig::Websocket,
                    default => ""
                }
            };
        }
    }
}