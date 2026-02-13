<?php
namespace Partarum\PartarumCLI\v_b\PartarumServer {

    /*
       * Ablauf:
               * um einen Server aufzusetzen ist es wichtig, das der Server als einzelne Datei in einer screen - session gestartet wird
                   ! man kann mehrere screen - sessions aus einer Datei heraus erstellen, aber nicht mehrere Server ohne screen - session
               * für jeden Server müssen Events erstellt werden
     */

    require_once "System/Execution/Registry.php";
    require_once "System/Executive.php";

    use Partarum\PartarumCLI\v_b\PartarumServer\System\Execution\Registry;
    use Partarum\PartarumCLI\v_b\PartarumServer\System\Executive;

    class ServerManager {

        /*
        *  User kann Server per PHP registrieren - damit Server während des Prozesses erstellt und eliminiert werden können, ohne dabei die anderen zu stören
        *  RegistryFile ( JSON - Format ) erstellen - für die wichtigsten Infos
        *  Server - File erstellen, welche dann vom User manipuliert werden kann
        *  Registrierung bestätigen
        */
        public static function addServer(string $ip, int $port) : array {

        }

        public static function removeServer(string $ip, int $port) : array {

        }

        public static function getServer(string $ip, int $port) : array {

        }

        public static function hasServer(string $ip, int $port){

        }
    }
}