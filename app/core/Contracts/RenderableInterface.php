<?php
declare(strict_types=1);

namespace app\core\Contracts;

interface RenderableInterface {

    public function render(): string;
}
