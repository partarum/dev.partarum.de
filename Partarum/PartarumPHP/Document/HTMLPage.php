<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Document {

    use Partarum\PartarumPHP\Traits\Document\FeatHTMLPage;

    class HTMLPage extends HTMLDocument {

        use FeatHTMLPage;

        public function __construct(?string $theme = null, string|bool|null $part = null) {

            parent::__construct();

            $this->setWrapperToParentObject();

            $this->open();

            $this->theme = $theme;

            if (($theme !== null) && ($part !== null)) {

                if (($theme !== "none") && ($theme !== "start")) {
                    $this->openWithWrapper($part);
                } else {
                    $this->openWithoutWrapper($part);
                }

                $this->etc = (($part !== "finish") && ($theme !== "start") && ($theme !== "none"))
                    ? new HTMLgetEtc($theme)
                    : false;

                if (($theme !== "start") && ($theme !== "none") && ($part !== "finish")) {
                    $this->setPage();
                }
            }
        }

        public function open(): void {

            parent::setBase();
            parent::setStructur();

            $path = $this->path;
            $this->head =& $this->dom->{$path["html"]}->{$path["head"]};
            $this->body =& $this->dom->{$path["html"]}->{$path["body"]};
        }

        protected function setDOMReference(): void {

            $path = $this->path;
            $this->header =& $this->dom->{$path["body"]}->{$path["header"]};
            $this->main =& $this->dom->{$path["body"]}->{$path["main"]};
            $this->footer =& $this->dom->{$path["body"]}->{$path["footer"]};
        }

        public function openWithWrapper(string|bool|null $part = true): void {

            $this->setWrapper($this->theme !== null);

            $this->setDOMReference();

            if (($part !== true) && (($part === "finish") || ($part === null))) {
                $this->close();
            }
        }

        public function openWithoutWrapper(string|bool|null $part = null): void {

            if (($part === "finish") || ($part === null)) {
                $this->close();
            }
        }

        public function setPage(): void {

        }

        public function close(): void {

            echo $this->getHTML();
        }
    }
}