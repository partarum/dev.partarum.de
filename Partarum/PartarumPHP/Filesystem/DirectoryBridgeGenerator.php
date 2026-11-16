<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Filesystem {

    use Generator;

    /*
     *  Generator 3
     */

    class DirectoryBridgeGenerator {

        public string $path;

        public function __construct(string $path) {

            $this->path = $path;
        }

        public function getFile(): Generator {

            $fileObject = new FilesystemGenerator($this->path);
            $fileGenerator = $fileObject->getFile();

            if ($fileGenerator->valid() === true) {
                foreach ($fileGenerator as $file) {
                    yield $file;
                }
            }
        }
    }
}
