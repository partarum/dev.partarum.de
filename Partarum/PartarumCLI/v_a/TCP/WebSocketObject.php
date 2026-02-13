<?php
namespace PartarumCLI\v_a\TCP {

    require "WebSocketContext.php";

    use stdClass;

    class WebSocketObject {

        public ?WebSocketContext $context;

        public ?string $address;

        public ?int $worker;

        public ?string $transport;

        public ?stdClass $routes;

        public function __construct() {

            $this->context = new WebSocketContext();

            $this->routes = new stdClass();
        }
    }
}
