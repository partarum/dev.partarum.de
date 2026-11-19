<?php
declare(strict_types=1);

use app\controllers\dashboard\DashboardController;
use app\modules\dashboard\DashboardStage;

DashboardController::handle(DashboardStage::Workspace)->dispatch();