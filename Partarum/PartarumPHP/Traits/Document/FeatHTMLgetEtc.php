<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Traits\Document {

    use Generator;
    use Partarum\PartarumPHP\Filesystem\FilesystemGenerator;
    use ReflectionClass;
    use Throwable;

    trait FeatHTMLgetEtc {

        public function getIterator(): void {

            $path = $this->filterPath();

            if (is_dir($path)) {
                $this->iterator = new FilesystemGenerator($path);
            } else {
                $this->iterator = null;
            }
        }

        public function filterPath(): string {

            $baseRoot = $this->resolveRootPath();

            return ($this->theme === "theOffice")
                ? $baseRoot . self::DASHBOARD_PATH
                : $baseRoot . self::HTML_PATH . $this->theme . "/surface/html";
        }

        protected function resolveRootPath(): string {

            if (defined("ROOT")) {
                return rtrim((string)ROOT, "/\\");
            }

            if (!empty($_SERVER["DOCUMENT_ROOT"])) {
                return rtrim((string)$_SERVER["DOCUMENT_ROOT"], "/\\");
            }

            return dirname(__DIR__, 4);
        }

        public function getEtcConst(string $namespacePath): Generator {

            try {
                $className = $namespacePath . "\\etc";
                if (class_exists($className)) {
                    $etc = new ReflectionClass($className);
                    yield $etc->getConstants();
                }
            } catch (Throwable $throwable) {
                error_log("[Partarum][FeatHTMLgetEtc] Reflection error for {$namespacePath}: " . $throwable->getMessage());
            }
        }

        public function setPattern(): void {

            $this->pattern = [
                "head" => "{" . preg_quote("head") . "$}",
                "header" => "{" . preg_quote("1#header") . "}",
                "main" => "{" . preg_quote("2#main") . "}",
                "footer" => "{" . preg_quote("3#footer") . "}"
            ];
        }

        public function setSurfacePath(string $subPath): Generator {

            yield "surface\\html\\" . $subPath;
        }
    }
}