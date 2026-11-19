<?php
declare(strict_types=1);

use app\controllers\Befragung\SurveyStageController;
use app\src\Befragung\Surface;

SurveyStageController::handle(Surface::TransitionStage)->dispatch();