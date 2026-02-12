<?php

namespace app\src\Befragung {

    use DateTime;
    use IntlDateFormatter;
    use Partarum;
    use Partarum\HTTP\API;

    enum Surface {

        case InitialStage;

        case SetupStage;

        case TransitionStage;

        case ExecutionStage;

        case FinalStage;

        public static function getCase($stage, string $theme): null | SurfaceInitialStage | SurfaceSetupStage | SurfaceTransitionStage | SurfaceExecutionStage | SurfaceFinalStage {

            return match($stage) {
                Surface::SetupStage => SurfaceSetupStage::tryFrom($theme),
                Surface::TransitionStage => SurfaceTransitionStage::tryFrom($theme),
                //Surface::ExecutionStage => SurfaceExecutionStage::tryFrom($theme),
                Surface::FinalStage => SurfaceFinalStage::tryFrom($theme),
                default => SurfaceInitialStage::tryFrom($theme)
            };
        }

        public static function getDate(string | null $dbDate = null): array | null {

            if($dbDate !== null) {

                $formatter = new IntlDateFormatter("de_DE", IntlDateFormatter::FULL, IntlDateFormatter::NONE);

                $formatter->setPattern("yyyy-MM-dd");

                $dateObject = $formatter->parse($dbDate);

                $date = $formatter->format($dateObject);

                $formatter->setPattern("yyyy-MM");

                $datetime = $formatter->format($dateObject);

                $formatter->setPattern("MMMM yyyy");

                $displayDate = $formatter->format($dateObject);

                return ["date" => $date, "datetime" => $datetime, "displayDate" => $displayDate];
            }

            return null;
        }

        public function init( null | array $configArray = NULL) : SurfaceApp {

            $config = (isset($configArray)) ? [...$configArray, "stage" => $this->name] : ["stage" => $this->name];

            $app = new SurfaceApp($config);

            $case = Surface::getCase($this, $app->url);

            $app->initCase($case);

            return $app;
        }

    }
}