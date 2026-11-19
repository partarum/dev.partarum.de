<?php
declare(strict_types=1);

namespace app\core\Enums;

    use app\core\Contracts\RenderableInterface;
    use app\views\Befragung\forms\KVHFormView;
    use app\views\Befragung\forms\LSHFormView;
    use app\views\Befragung\forms\NSHFormView;
    use app\views\Befragung\forms\RHBFormView;
    use app\views\Befragung\forms\RHDFormView;

    enum SurveyTopic: string {

        case KVH = "KVH";
        case LSH = "LSH";
        case NSH = "NSH";
        case RHB = "RHB";
        case RHD = "RHD";

        public static function fromCaseName(?string $name): ?self {

            if ($name === null || $name === "") {
                return null;
            }

            return self::tryFrom(strtoupper(trim($name, "/")));
        }

        public function getTitle(): string {

            return match ($this) {
                self::KVH => "Konstruktionsvollholz",
                self::LSH => "Laubschnittholz",
                self::NSH => "Nadelschnittholz",
                self::RHB => "Rundholzmärkte in Baden-Württemberg",
                self::RHD => "Rundholzmärkte in Deutschland"
            };
        }

        public function getPartnerString(): string {

            return match ($this) {
                self::KVH => "Im Auftrag der Überwachungsgemeinschaft Konstruktionsvollholz e.V., Wuppertal:",
                self::LSH, self::NSH, self::RHD => "In Zusammenarbeit mit dem DRW-Verlag Leinfelden-Echterdingen",
                self::RHB => "Im Auftrag der Forstkammer Baden-Württemberg, Stuttgart:"
            };
        }

        public function getThemeStylesheet(): string {

            return "/public/assets/css/themes/{$this->value}/theme.css";
        }

        public function getExecutionUrl(string $displayDate): string {

            $valDate = str_replace(" ", "_", $displayDate);

            return "{$this->value}-Befragung?date={$valDate}";
        }

        public function getFinalActionUrl(): string {

            return "./{$this->value}-Final";
        }

        public function getExecutionMainClass(): string {

            return match ($this) {
                self::KVH => "ie-main pre-pfm-execution-stage",
                default => "ie-main"
            };
        }

        public function createFormView(string $displayDate): RenderableInterface {

            return match ($this) {
                self::KVH => new KVHFormView($displayDate),
                self::LSH => new LSHFormView($displayDate),
                self::NSH => new NSHFormView($displayDate),
                self::RHB => new RHBFormView($displayDate),
                self::RHD => new RHDFormView($displayDate)
            };
        }
    }
