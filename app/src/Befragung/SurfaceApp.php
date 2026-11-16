<?php
declare(strict_types=1);

namespace app\src\Befragung {

    use Partarum\Partarum;
    use Partarum\PartarumPHP\HTTP\API;
    use Medoo\Medoo;

    class SurfaceApp {

        public Partarum $app;

        public string $stage;

        public null | SurfaceInitialStage | SurfaceSetupStage | SurfaceTransitionStage | SurfaceExecutionStage | SurfaceFinalStage $case = null;

        public ?string $caseName = null;

        public string $appName = "";

        public string $topic = "";

        public API $api;

        public array $env = [];

        public array $config;

        public Medoo $db;

        public string $uri;

        public string $url;

        /**
         * @var null|string
         */
        public null | string $ref = null;


        public function __construct(array $config) {

            $this->config = $config;

            $this->stage = $config["stage"] ?? "";

            $this->app = new Partarum($this->stage ?: "Partarum");

            $this->api = new API();

            (isset($this->config["env"])) && $this->loadENV();

            $this->initDB();

            $this->uri = $_SERVER["REQUEST_URI"] ?? "/";
            $this->url = $_SERVER["REDIRECT_URL"] ?? (parse_url($this->uri, PHP_URL_PATH) ?: "/");
            $this->ref = $_SERVER["HTTP_REFERER"] ?? null;
        }

        private function loadENV(): void {

            $this->env = $this->app->loadSecret($this->config["env"]);
        }

        private function initDB(): void {

            $this->db = $this->app->createDBConnection();
        }

        public function initCase(null | SurfaceInitialStage | SurfaceSetupStage | SurfaceTransitionStage | SurfaceExecutionStage | SurfaceFinalStage $case): void {

            $this->case = $case;

            $this->caseName = $case?->name;
        }
    }
}