<?php
namespace Partarum\PartarumCLI\v_b\PartarumServer\System\Handle {

    enum CacheType : int {

        case IP = 0x0001;
        case Socket = 0x0002;
        case Websocket = 0x0004;

        public function getFilePath() : string {

            return match($this){
                CacheType::IP => "Partarum/PartarumCLI/v_b/PartarumServer/Cache/ip.json",
                CacheType::Socket=> "Partarum/PartarumCLI/v_b/PartarumServer/Cache/socket.json",
                CacheType::Websocket => "Partarum/PartarumCLI/v_b/PartarumServer/Cache/websocket.json"
            };
        }

    }
}