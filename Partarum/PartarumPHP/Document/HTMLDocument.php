<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Document {

    use AllowDynamicProperties;
    use DOMImplementation;
    use stdClass;

    #[AllowDynamicProperties]
    class HTMLDocument extends DOMImplementation {

        public stdClass $dom;

        public array $path = [];

        public mixed $head = null;

        public mixed $body = null;

        public function setStructur(): void {

            $path = $this->path;

            $this->dom->{$path["html"]}->{$path["head"]} = $this->dom->{$path["dom"]}->createElement("head");
            $this->dom->{$path["surface"]}->appendChild($this->dom->{$path["html"]}->{$path["head"]});

            $this->dom->{$path["html"]}->{$path["body"]} = $this->dom->{$path["dom"]}->createElement("body");
            $this->dom->{$path["surface"]}->appendChild($this->dom->{$path["html"]}->{$path["body"]});

            $this->head =& $this->dom->{$path["html"]}->{$path["head"]};
            $this->body =& $this->dom->{$path["html"]}->{$path["body"]};
        }

        public function setBase(): void {

            $doctype = parent::createDocumentType("html");

            $path = $this->path;

            $this->dom->{$path["dom"]} = parent::createDocument(null, "html", $doctype);
            $this->dom->{$path["dom"]}->formatOutput = true;
            $this->dom->{$path["dom"]}->encoding = "utf-8";

            $this->dom->{$path["surface"]} = $this->dom->{$path["dom"]}->documentElement;
            $this->dom->{$path["surface"]}->setAttribute("lang", "de");
        }

        public function setParentObject(): void {

            $this->dom = new stdClass();

            $this->path["dom"] = "dom";
            $this->path["surface"] = "surface";
            $this->path["html"] = "surface\\html";
            $this->path["head"] = $this->path["html"] . "\\head";
            $this->path["body"] = $this->path["html"] . "\\body";

            $path = $this->path;

            $this->dom->{$path["html"]} = new stdClass();
            $this->dom->{$path["head"]} = new stdClass();
            $this->dom->{$path["body"]} = new stdClass();
        }

        public function getHTML(): string {

            return (string)$this->dom->dom->saveHTML();
        }

        public function set(): void {

            echo $this->getHTML();
        }

        public function __construct() {

            $this->setParentObject();
            $this->setBase();
            $this->setStructur();
        }
    }
}
