<?php
namespace PartarumCLI\v_a\System\Filesystem {

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