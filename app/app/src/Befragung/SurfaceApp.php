<?php
namespace app\src\Befragung {

    if (!class_exists('Partarum')) {
        require_once $_SERVER["DOCUMENT_ROOT"] . "/Partarum/Partarum.php";
    }

    use Partarum;
    use Partarum\HTTP\API;
    use Medoo\Medoo;
    use app\src\Befragung\SurfaceInitialStage;
    use app\src\Befragung\SurfaceSetupStage;
    use app\src\Befragung\SurfaceTransitionStage;
    use app\src\Befragung\SurfaceExecutionStage;
    use app\src\Befragung\SurfaceFinalStage;

    class SurfaceApp {

        public Partarum $app;

        public string $stage;

        public SurfaceInitialStage | SurfaceSetupStage | SurfaceTransitionStage | SurfaceExecutionStage | SurfaceFinalStage $case;

        public string $caseName;

        public string $appName;

        public string $topic;

        public API $api;

        public array $env;

        public array $config;

        public Medoo $db;

        public string $uri;

        public string $url;

        /**
         * @var null|string         */

        public null | string $ref;


        public function __construct(array $config) {

            $this->config = $config;

            $this->stage = $config["stage"];

            $this->app =  new Partarum($this->stage);

            $this->api = new API();

            (isset($this->config["env"])) && $this->loadENV();

            $this->initDB();

            $this->uri = $_SERVER["REQUEST_URI"];
            $this->url = $_SERVER["REDIRECT_URL"];
            $this->ref = $_SERVER["HTTP_REFERER"] ?? NULL;
        }

        private function loadENV(): void {

            $this->env = $this->app->loadSecret($this->config["env"]);
        }

        private function initDB(): void {

            $this->db = $this->app->createDBConnection();
        }

        public function initCase(SurfaceInitialStage | SurfaceSetupStage | SurfaceTransitionStage | SurfaceExecutionStage | SurfaceFinalStage $case): void {

            $this->case = $case;

            $this->caseName = $case->name;
        }
    }
}