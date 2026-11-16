<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP {

    use Generator;
    use Partarum\PartarumPHP\Config\GlobalConfig;
    use Partarum\PartarumPHP\Document\HTMLPage;
    use Partarum\PartarumPHP\Security\SessionWorker;
    use ReflectionClass;
    use Throwable;

    class Office {

        public HTMLPage $page;
        public ?array $config = null;
        public mixed $system = null;
        public mixed $runTime = null;
        public mixed $sessionHandler = null;

        public mixed $metaUTF8 = null;
        public ?string $slash = null;

        public function __construct(?string $theme = null, string|bool|null $part = null, bool $setSession = false) {

            if (GlobalConfig::$ses === 1) {
                new SessionWorker($setSession);
            }

            $wrapper = GlobalConfig::$wrapper ?? false;

            $this->page = self::htmlPage($theme, $part, $wrapper);
        }

        public static function htmlPage(?string $theme = null, string|bool|null $part = null, bool $wrapper = false): HTMLPage {

            return new HTMLPage($theme, $part);
        }

        public function getConfigPage(): void {

            try {
                $configFile = ($_SERVER["DOCUMENT_ROOT"] ?? "") . "/oldSite/etc/configPage.php";
                if (is_file($configFile)) {
                    include_once $configFile;
                }
                if (class_exists("etc\\configPage")) {
                    $class = new ReflectionClass("etc\\configPage");
                    $this->config = $class->getConstants();
                }
            } catch (Throwable $throwable) {
                error_log("[Partarum][Office] Error loading configPage: " . $throwable->getMessage());
            }
        }

        public function setConfigSession(): void {

            if (isset($this->config["SES"])) {
                $this->setSession();
            }
        }

        public function setSession(): void {

            if (!isset($this->config["SES"]) || !class_exists(__NAMESPACE__ . "\\Runtime")) {
                return;
            }

            $runtimeClass = __NAMESPACE__ . "\\Runtime";

            if ($this->config["SES"] == 1) {
                $this->runTime = new $runtimeClass(1);
            } elseif ($this->config["SES"] == 2) {
                $this->runTime = new $runtimeClass(2);
                if (!defined("USER_SES") && isset($this->runTime->userSes)) {
                    define("USER_SES", $this->runTime->userSes);
                }
            }
        }

        public static function setHTTPLocation(string $userIP): Generator {

            $headerValue = ($userIP === "127.0.0.1")
                ? "Location: http://localhost/index.php"
                : "Location: https://localhost/html/index.php";

            yield $headerValue;
        }
    }
}