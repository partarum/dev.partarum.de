<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Security\Token {

    class JWTHeader {

        public ?string $typ = NULL;

        public ?string $algo = NULL;
    }
}