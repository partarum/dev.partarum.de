<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Service\Binary {

    class FlagWorker {

        protected ?int $flags = null;

        private array $cache = [];

        public function __construct() {

        }

        public function isFlagSet(int $flag): bool {
            return (($this->flags ?? 0) & $flag) === $flag;
        }

        public function setFlags(int $flag, bool $value = false): void {

            if ($value === true) {
                if (isset($this->flags)) {

                    $this->flags |= $flag;

                } else {

                    $this->flags = $flag;
                }
            } else {

                $this->flags = ($this->flags ?? 0) & ~$flag;
            }
        }
    }
}