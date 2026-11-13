<?php
namespace Partarum\PartarumSurface\Stages {

    interface InitialStageInterface {

        public function getTitle(): string;

        public function getRedirectUrl($date): string;
    }
}
