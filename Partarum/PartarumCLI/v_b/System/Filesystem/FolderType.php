<?php
namespace Partarum\PartarumCLI\v_b\System\Filesystem {

    use Partarum\PartarumCLI\v_b\System\Filesystem\FolderPath;

    enum FolderType {

        case Partarum;
        case Base;
        case All;
        case User;

        public function filter( string $needle): null | FolderPath {

            return match($this){
                FolderType::Partarum, FolderType::Base, FolderType::All => FolderPath::get($needle)
            };
        }
    }
}