<?php
namespace app\core\SurfaceExample {

    use PartarumSurface\Stages\TransitionStageInterface;

    enum SurfaceTransitionStage : string implements TransitionStageInterface {

        case KVH = "/KVH-Befragung";

        case LSH = "/LSH-Befragung";

        case NSH = "/NSH-Befragung";

        case RHB = "/RHB-Befragung";

        case RHD = "/RHD-Befragung";
    }
}