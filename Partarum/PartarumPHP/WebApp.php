<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP {

    use Partarum\PartarumPHP\Office;
    use Partarum\PartarumPHP\Config\GlobalConfig;
    use Partarum\PartarumPHP\Security\SafetyService;


    class WebApp {

        public ?string $theme = null;

        public int $ses = 0;

        public ?string $style = null;

        public int | bool $wrapper = 0;

        public int $presentation = 1;

        public int $public = 1;

        public int $private = 0;


        public function create(): void {

            GlobalConfig::setTheme($this->theme);
            GlobalConfig::setSes($this->ses);
            GlobalConfig::setStyle($this->style);
            GlobalConfig::setPresentation($this->presentation);
            GlobalConfig::setPublic($this->public);
            GlobalConfig::setPrivate($this->private);
            GlobalConfig::setWrapper(!($this->wrapper === 0 || $this->wrapper === false));

            ($this->public === 0) && new SafetyService();

            $theme = $this->theme;

            $html = new Office($theme, "start", true);

            $html->page->close();
        }
    }
}