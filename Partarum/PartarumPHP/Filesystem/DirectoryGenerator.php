<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Filesystem {

    use FilesystemIterator;
    use Generator;

    class DirectoryGenerator {

        public string $path;

        public function __construct(string $path) {

            $this->path = $path;
        }

        public function getFilesystem(): Generator {

            if ($this->path !== "" && is_dir($this->path)) {
                $result = new FilesystemIterator(
                    $this->path,
                    FilesystemIterator::CURRENT_AS_SELF |
                    FilesystemIterator::SKIP_DOTS |
                    FilesystemIterator::FOLLOW_SYMLINKS
                );

                foreach ($result as $file) {
                    yield $file;
                }
            }
        }
    }
}