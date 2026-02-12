<?php
namespace app\core\SurfaceExample {

    use PartarumSurface\Stages\FinalStageInterface;

    enum SurfaceFinalStage : string implements FinalStageInterface
    {

        case KVH = "/KVH-Final";

        case LSH = "/LSH-Final";

        case NSH = "/NSH-Final";

        case RHB = "/RHB-Final";

        case RHD = "/RHD-Final";

        public function getTitle(): string {

            return match($this) {
                SurfaceFinalStage::KVH => "Konstruktionsvollholz",
                SurfaceFinalStage::LSH => "Laubschnittholz",
                SurfaceFinalStage::NSH => "Nadelschnittholz",
                SurfaceFinalStage::RHB => "Rundholzmärkte in Baden-Württemberg",
                SurfaceFinalStage::RHD => "Rundholzmärkte in Deutschland"
            };
        }

    }
}