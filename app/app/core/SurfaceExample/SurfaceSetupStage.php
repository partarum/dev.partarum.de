<?php
namespace app\core\SurfaceExample {

    use PartarumSurface\Stages\SetupStageInterface;

    enum SurfaceSetupStage : string implements SetupStageInterface {

        case KVH = "KVH";

        case LSH = "LSH";

        case NSH = "NSH";

        case RHB = "RHB";

        case RHD = "RHD";

        public function getPartnerString(): string {

            return match($this) {
                SurfaceSetupStage::KVH => "Im Auftrag der Überwachungsgemeinschaft Konstruktionsvollholz e.V., Wuppertal:",
                SurfaceSetupStage::LSH, SurfaceSetupStage::NSH, SurfaceSetupStage::RHD => "In Zusammenarbeit mit dem DRW-Verlag Leinfelden-Echterdingen",
                SurfaceSetupStage::RHB => "Im Auftrag der Forstkammer Baden-Württemberg, Stuttgart:"
            };
        }

    }
}