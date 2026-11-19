<?php
declare(strict_types=1);

namespace app\core\Contracts;

interface ControllerInterface {

    public function dispatch(): void;
}
