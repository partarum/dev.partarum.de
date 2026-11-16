<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Filesystem {

    use Generator;

    /*
     *  Generator 2
     */

    class FilesystemGenerator {

        public mixed $fileGenerator = null;

        public string $path;

        public function __construct(string $path) {

            $this->path = $path;
        }

        public function getFile(): Generator {

            $fileGenerator = new DirectoryGenerator($this->path);
            $fileObject = $fileGenerator->getFilesystem();

            foreach ($fileObject as $file) {

                $fileName = $file->getFilename();

                if ($fileName !== "") {
                    if ($file->isFile()) {
                        yield $file;
                    } elseif ($file->isDir()) {
                        $result = new DirectoryBridgeGenerator($file->getPathname());
                        $resultFile = $result->getFile();

                        if ($resultFile->valid() !== false) {
                            foreach ($resultFile as $nextFile) {
                                yield $nextFile;
                            }
                        }
                    }
                }
            }
        }
    }
}