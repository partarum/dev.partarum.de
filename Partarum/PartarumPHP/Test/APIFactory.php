<?php
namespace Partarum\PartarumPHP\Test {

    use Partarum\PartarumPHP\Design\TraidChain\Factory;

    class APIFactory extends Factory{


        public function createRequest(){

            return $this->createArea(APIRequest::class);
        }

        public function createResponse(){

            return $this->createArea(APIResponse::class);

        }
    }
}