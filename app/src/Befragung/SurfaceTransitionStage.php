<?php
declare(strict_types=1);

namespace app\src\Befragung {

    use Partarum\PartarumSurface\Stages\TransitionStageInterface;

    enum SurfaceTransitionStage: string implements TransitionStageInterface {

        case KVH = "/KVH-Befragung";

        case LSH = "/LSH-Befragung";

        case NSH = "/NSH-Befragung";

        case RHB = "/RHB-Befragung";

        case RHD = "/RHD-Befragung";

        public function getTitle(): string {

            return match($this) {
                SurfaceTransitionStage::KVH => "Konstruktionsvollholz",
                SurfaceTransitionStage::LSH => "Laubschnittholz",
                SurfaceTransitionStage::NSH => "Nadelschnittholz",
                SurfaceTransitionStage::RHB => "Rundholzmärkte in Baden-Württemberg",
                SurfaceTransitionStage::RHD => "Rundholzmärkte in Deutschland"
            };
        }
    }
}