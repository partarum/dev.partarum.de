<?php
declare(strict_types=1);

namespace app\core\Traits;

use Medoo\Medoo;

trait FeatModelDatabase {

    protected Medoo $db;

    public function getDatabase(): Medoo {

        return $this->db;
    }
}
