<?php
namespace app\src\Befragung {

    enum SurfaceTransitionStage : string {

        case KVH = "/KVH-Befragung";

        case LSH = "/LSH-Befragung";

        case NSH = "/NSH-Befragung";

        case RHB = "/RHB-Befragung";

        case RHD = "/RHD-Befragung";
    }
}