<?php
declare(strict_types=1);

namespace app\modules\dashboard;

enum DashboardStage: string {

    case Login = "/loginPage";
    case Logout = "/logoutPage";
    case Authenticate = "/dashboard";
    case Workspace = "/dashboardPage";

    public function getTitle(): string {

        return "Dr. Franz-Josef Lückge Consulting";
    }

    public function getStylesheet(): string {

        return match ($this) {
            self::Login, self::Logout => "/public/assets/css_backup/login.css",
            self::Workspace, self::Authenticate => "/public/assets/css_backup/dashboard.css"
        };
    }
}
