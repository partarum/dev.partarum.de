<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Filesystem {

    use FilesystemIterator;
    use Partarum\PartarumCLI\v_b\System\Root;
    use Partarum\PartarumPHP\Service\Binary\FlagWorker;
    use Partarum\PartarumPHP\Traits\Filesystem\FeatFilesystem;

    class Filesystem extends FlagWorker {

        use FeatFilesystem;

        public const RECURSIVE = 2;

        public const ONLY = 4;

        public const ABSOLUTE = 8;

        public const DOCUMENT_ROOT = 16;

        public static array $allFiles = [];

        public static array $allIterators = [];

        public function __construct(?string $path = null, int $flag = Filesystem::ONLY) {

            parent::__construct();

            $this->setFlags($flag, true);

            $aktVer = ($path !== null && $path !== "") ? $path : (getcwd() ?: ".");

            if (!is_dir($aktVer)) {
                return;
            }

            $iterator = new FilesystemIterator(
                $aktVer,
                FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_SELF | FilesystemIterator::SKIP_DOTS
            );

            $docRoot = Root::$DOCUMENT_ROOT
                ?? (defined("ROOT") ? (string)ROOT : (string)($_SERVER["DOCUMENT_ROOT"] ?? ""));

            foreach ($iterator as $fileInform => $current) {

                $filePath = (string)$fileInform;
                $type = $iterator->isDir() ? "folder" : "file";

                if ($type === "file") {

                    self::$allFiles[] = ($this->isFlagSet(self::DOCUMENT_ROOT) && $docRoot !== "")
                        ? str_replace($docRoot, "", $filePath)
                        : $filePath;

                    self::$allIterators[] = pathinfo($filePath);
                }

                self::setFileFolder([
                    $filePath,
                    $iterator->getFilename(),
                    $iterator->getATime(),
                    $iterator->getMTime()
                ], $type, $path ?? $aktVer, ($this->isFlagSet(self::ABSOLUTE) ? self::ABSOLUTE : self::DOCUMENT_ROOT));

                if ($this->isFlagSet(self::RECURSIVE) && $type === "folder") {
                    $realPath = $iterator->getRealPath();
                    if (is_string($realPath) && $realPath !== "") {
                        $this->nextRound($realPath);
                    }
                }
            }
        }

        private function nextRound(string $path): void {

            new Filesystem($path, (int)$this->flags);
        }

        public function getAllFiles(): array {

            return self::$allFiles;
        }

        public function getAllIterator(): array {

            return self::$allIterators;
        }

        public static function clearCache(): void {

            self::$allFiles = [];
            self::$allIterators = [];
        }
    }
}