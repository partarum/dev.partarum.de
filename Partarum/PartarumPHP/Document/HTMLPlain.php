<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Document {

    use DOMDocument;
    use DOMElement;
    use DOMImplementation;
    use DOMText;

    class HTMLPlain {

        public DOMImplementation $html;

        public DOMDocument $dom;

        public ?DOMElement $surface = null;

        public ?DOMElement $head = null;

        public ?DOMElement $body = null;

        public function __construct() {

            $this->html = new DOMImplementation();
            $doctype = $this->html->createDocumentType("html");

            $this->dom = $this->html->createDocument(null, "html", $doctype);
            $this->dom->formatOutput = true;
            $this->dom->encoding = "utf-8";

            $this->surface = $this->dom->documentElement;
            $this->surface?->setAttribute("lang", "de");

            $this->setHead();
            $this->setBody();
            $this->setViewport();
        }

        public function setScript(string $fileName, bool $module = false): DOMElement {

            $script = $this->dom->createElement("script");
            $this->head?->appendChild($script);

            $script->setAttribute("src", $fileName);
            if ($module !== false) {
                $script->setAttribute("type", "module");
            }

            return $script;
        }

        public function setLink(string $fileName): DOMElement {

            $link = $this->dom->createElement("link");
            $this->head?->appendChild($link);

            $link->setAttribute("href", $fileName);
            $link->setAttribute("rel", "stylesheet");
            $link->setAttribute("type", "text/css");

            return $link;
        }

        public function setTitle(string $text): DOMElement {

            $title = $this->dom->createElement("title");
            $this->head?->appendChild($title);

            $title->appendChild(new DOMText($text));

            return $title;
        }

        protected function setHead(): void {

            $this->head = $this->dom->createElement("head");
            $this->surface?->appendChild($this->head);
        }

        protected function setBody(): void {

            $this->body = $this->dom->createElement("body");
            $this->surface?->appendChild($this->body);
            $this->surface?->setAttribute("style", "background-color: #f2f5f7");
        }

        protected function setViewport(): void {

            $viewport = $this->dom->createElement("meta");
            $this->head?->appendChild($viewport);

            $viewport->setAttribute("name", "viewport");
            $viewport->setAttribute("content", "width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui");
        }

        public function getHTML(): string {

            return (string)$this->dom->saveHTML();
        }

        public function close(): void {

            echo $this->getHTML();
        }
    }
}