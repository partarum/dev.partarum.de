<?php
namespace app\src\Befragung {
    enum SurfaceInitialStage: string
    {

        case KVH = "/KVH";

        case LSH = "/LSH";

        case NSH = "/NSH";

        case RHB = "/RHB";

        case RHD = "/RHD";

        public function getTitle(): string {

            return match($this) {
                SurfaceInitialStage::KVH => "Konstruktionsvollholz",
                SurfaceInitialStage::LSH => "Laubschnittholz",
                SurfaceInitialStage::NSH => "Nadelschnittholz",
                SurfaceInitialStage::RHB => "Rundholzmärkte in Baden-Württemberg",
                SurfaceInitialStage::RHD => "Rundholzmärkte in Deutschland"
            };
        }

        public function getRedirectUrl($date): string {

            $valDate = str_replace(" ", "_", $date);

            return match($this) {
                SurfaceInitialStage::KVH => "KVH-Befragung?date=" . $valDate,
                SurfaceInitialStage::LSH => "LSH-Befragung?date=" . $valDate,
                SurfaceInitialStage::NSH => "NSH-Befragung?date=" . $valDate,
                SurfaceInitialStage::RHB => "RHB-Befragung?date=" . $valDate,
                SurfaceInitialStage::RHD => "RHD-Befragung?date=" . $valDate
            };
        }

    }
}