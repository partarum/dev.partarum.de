<?php
declare(strict_types=1);

namespace app\core\Traits;

trait FeatHtmlEscape {

    protected function escape(string | int | float | null $value): string {

        return htmlspecialchars((string)($value ?? ""), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
    }
}
