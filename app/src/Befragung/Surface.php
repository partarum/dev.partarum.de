<?php
declare(strict_types=1);

namespace app\src\Befragung {

    use IntlDateFormatter;

    enum Surface {

        case InitialStage;

        case SetupStage;

        case TransitionStage;

        case ExecutionStage;

        case FinalStage;

        public static function getCase(Surface $stage, string $theme): null | SurfaceInitialStage | SurfaceSetupStage | SurfaceTransitionStage | SurfaceExecutionStage | SurfaceFinalStage {

            return match($stage) {
                Surface::SetupStage => SurfaceSetupStage::tryFrom($theme),
                Surface::TransitionStage => SurfaceTransitionStage::tryFrom($theme),
                Surface::ExecutionStage => method_exists(SurfaceExecutionStage::class, "tryFrom") ? SurfaceExecutionStage::tryFrom($theme) : null,
                Surface::FinalStage => SurfaceFinalStage::tryFrom($theme),
                default => SurfaceInitialStage::tryFrom($theme)
            };
        }

        public static function getDate(string | null $dbDate = null): array | null {

            if($dbDate !== null) {

                $formatter = new IntlDateFormatter("de_DE", IntlDateFormatter::FULL, IntlDateFormatter::NONE);

                $formatter->setPattern("yyyy-MM-dd");

                $dateObject = $formatter->parse($dbDate);

                if($dateObject === false) {
                    return null;
                }

                $date = $formatter->format($dateObject);

                $formatter->setPattern("yyyy-MM");

                $datetime = $formatter->format($dateObject);

                $formatter->setPattern("MMMM yyyy");

                $displayDate = $formatter->format($dateObject);

                return ["date" => $date, "datetime" => $datetime, "displayDate" => $displayDate];
            }

            return null;
        }

        public function init(null | array $configArray = null): SurfaceApp {

            $config = (isset($configArray)) ? [...$configArray, "stage" => $this->name] : ["stage" => $this->name];

            $app = new SurfaceApp($config);

            $case = Surface::getCase($this, $app->url);

            if($case !== null) {
                $app->initCase($case);
            }

            return $app;
        }

    }
}