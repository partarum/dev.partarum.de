<?php
declare(strict_types=1);

namespace app\core\Contracts;

use Medoo\Medoo;

interface ModelInterface {

    public function getDatabase(): Medoo;
}
