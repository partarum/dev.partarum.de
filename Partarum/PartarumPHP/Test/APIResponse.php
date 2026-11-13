<?php
namespace Partarum\PartarumPHP\Test {

    use Partarum\PartarumPHP\Design\TraidChain\Factory;
    use Partarum\PartarumPHP\HTTP\Response\Channel;
    use Partarum\PartarumPHP\Security\Token\UUID;

    class APIResponse extends Factory {

        private Channel $channel;

        private string $id;

        public function __construct() {

            $this->id = UUID::v4()->current();

            $this->channel = $this->createArea(Channel::class);
        }

        public function setHeader(){

            $this->createWorker();
        }

        public function setBody(mixed $value, mixed $condition) : void {

            echo "<h3> setBody - value </h3>";
            print_r($value);

            echo "<h3> setBody - condition </h3>";
            print_r($condition);

            $this->createWorker();
        }


    }
}