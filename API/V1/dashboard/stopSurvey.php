<?php
declare(strict_types=1);

use app\controllers\dashboard\DashboardApiController;
use app\modules\dashboard\DashboardApiAction;

DashboardApiController::handle(DashboardApiAction::StopSurvey)->dispatch();