<?php
declare(strict_types=1);

namespace app\modules\dashboard;

enum DashboardApiAction: string {

    case CheckSurvey = "checkSurvey";
    case StartSurvey = "startSurvey";
    case StopSurvey = "stopSurvey";
    case GetExcelData = "getExcelData";
    case GetExcelFile = "getExcelFile";
    case GetToken = "getToken";
    case GetTokenExcel = "getTokenExcel";

    public function getPartarumAppName(): string {

        return match ($this) {
            self::GetTokenExcel => "getToken",
            default => $this->value
        };
    }

    public function getForbiddenPayload(?string $bearerToken): array {

        return match ($this) {
            self::GetExcelFile, self::GetTokenExcel => [
                "status" => "Your beer is stale - go home!"
            ],
            default => [
                "status" => "Your beer is stale",
                "beer" => $bearerToken
            ]
        };
    }
}
