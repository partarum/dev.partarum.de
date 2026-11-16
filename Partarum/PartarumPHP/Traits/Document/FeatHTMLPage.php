<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Traits\Document {

    use DOMDocument;
    use Generator;
    use Partarum\PartarumPHP\Config\GlobalConfig;
    use Partarum\PartarumPHP\Document\HTMLArticle;
    use Partarum\PartarumPHP\Document\HTMLElement;
    use Partarum\PartarumPHP\Document\HTMLSeries;
    use Partarum\PartarumPHP\Document\HTMLService;
    use stdClass;

    trait FeatHTMLPage {

        public mixed $head = null;
        public mixed $body = null;
        public mixed $wrapper = null;
        public mixed $header = null;
        public mixed $main = null;
        public mixed $footer = null;
        public mixed $etc = null;
        public array $user = [];
        public mixed $metaUTF8 = null;
        public mixed $config = null;
        public array $tagID = [];
        public ?string $theme = null;

        public array $fixed = [
            "surface",
            "surface\\html",
            "surface\\html\\head",
            "surface\\html\\body",
            "surface\\html\\body\\header",
            "surface\\html\\body\\main",
            "surface\\html\\body\\footer"
        ];

        protected static function ensureUtf8(string $text): string {

            if (mb_check_encoding($text, "UTF-8")) {
                return $text;
            }

            return mb_convert_encoding($text, "UTF-8", "ISO-8859-1");
        }

        public function setWrapperToParentObject(): void {

            $this->path["header"] = $this->fixed[4];
            $this->path["main"] = $this->fixed[5];
            $this->path["footer"] = $this->fixed[6];

            $wrapper = [
                $this->fixed[4],
                $this->fixed[5],
                $this->fixed[6]
            ];

            foreach ($wrapper as $path) {
                $this->dom->$path = new stdClass();
            }
        }

        public function getDOM(): DOMDocument {

            return $this->dom->dom;
        }

        public function setWrapper(bool $etc = true): void {

            $path = $this->path;
            $html = $path["html"];
            $body = $path["body"];
            $header = $path["header"];
            $main = $path["main"];
            $footer = $path["footer"];

            if (!isset($this->dom->$body->$header) || !($this->dom->$body->$header instanceof HTMLElement)) {
                $this->dom->$body->$header = new HTMLElement("header", $this->dom->$html->$body);
                $this->dom->$body->$header->id = "globalHeader";
            }

            if (!isset($this->dom->$body->$main) || !($this->dom->$body->$main instanceof HTMLElement)) {
                $this->dom->$body->$main = new HTMLElement("main", $this->dom->$html->$body);
                $this->dom->$body->$main->id = "content";
            }

            if (!isset($this->dom->$body->$footer) || !($this->dom->$body->$footer instanceof HTMLElement)) {
                $this->dom->$body->$footer = new HTMLElement("footer", $this->dom->$html->$body);
                $this->dom->$body->$footer->id = "globalFooter";
            }

            if ($etc !== false) {

                $this->dom->$html->$body->class = "flexibal flexColumn allScreen";

                $this->dom->$body->$header->class = "notOverTheLine flexibal flexRow flexWrap";

                if (class_exists(GlobalConfig::class)) {

                    if (GlobalConfig::$theme !== "theOffice") {

                        if (GlobalConfig::$style === "row") {

                            $this->dom->$body->$main->class = "flexibal flexRow flexWrap spaceAround";

                        } elseif (GlobalConfig::$style === "column") {

                            $this->dom->$body->$main->class = "flexibal flexColumn flexWrap spaceAround";
                        }
                    } elseif (GlobalConfig::$theme === "theOffice") {

                        $this->dom->$body->$main->class = "flexibal flexRow flexWrap flexStart";
                    }
                }

                $this->dom->$body->$footer->class = "wrapperFooter";
            }
        }

        public function setScript(string $fileName): HTMLElement {

            $script = new HTMLElement("script", $this->head);
            $script->setAttribute("src", $fileName);
            return $script;
        }

        public function setLink(string $fileName): HTMLElement {

            $link = new HTMLElement("link", $this->head);
            $link->setAttribute("href", $fileName);
            $link->setAttribute("rel", "stylesheet");
            $link->setAttribute("type", "text/css");
            return $link;
        }

        public function setTitle(string $text): HTMLElement {

            $title = new HTMLElement("title", $this->dom->{"surface\\html"}->{"surface\\html\\head"});
            $title->text = self::ensureUtf8($text);
            return $title;
        }

        public function positionNeedle(string $string): Generator {

            $needle = "surface";
            $positionNeedle = stripos($string, $needle);

            yield $positionNeedle !== false ? substr($string, $positionNeedle) : $string;
        }

        public function has_string_keys(array $array): bool {

            return count(array_filter(array_keys($array), "is_string")) > 0;
        }

        public function isAssoc(array $arr): bool {

            if ([] === $arr) {
                return false;
            }

            return array_keys($arr) !== range(0, count($arr) - 1);
        }

        public function getEtcKeys(iterable $etc): Generator {

            foreach ($etc as $key => $value) {
                yield $key;
            }
        }

        public function getAttributeValue(mixed $value): Generator {

            if (is_array($value) && !$this->isAssoc($value)) {
                yield implode(" ", $value);
            } else {
                yield $value;
            }
        }

        public function setTextValue(mixed $attribute, bool $loged): Generator {

            if (is_array($attribute)) {

                if (isset($attribute["member"]) && ($loged === true)) {
                    yield $attribute["member"];
                } elseif (isset($attribute["public"]) && ($loged === false)) {
                    yield $attribute["public"];
                }
            }
        }

        public function setEtc(string $parentPath, string $domPath, array $etc, string $tagName): void {

            if (isset($this->dom->$parentPath)) {

                $node = $this->dom->$parentPath->$domPath ?? false;

                $loged = false;

                $etcID = $etc["ID"] ?? false;

                $etcKeys = $this->getEtcKeys($etc);

                foreach ($etcKeys as $value) {

                    if ($value !== "COUNT") {

                        $little = ($value === "CLASSNAMES") ? "class" : strtolower((string)$value);

                        $getAttrValue = $this->getAttributeValue($etc[$value]);

                        $attribute = $getAttrValue->current();

                        if ($value === "TEXT") {

                            $getTextValue = $this->setTextValue($attribute, $loged);

                            foreach ($getTextValue as $attr) {
                                $attribute = $attr;
                            }
                        }

                        if (($value === "TEXTFILE") || ($value === "TEMPLATE")) {

                            if (isset($this->etc->templatePaths->$domPath)) {

                                if (in_array($this->etc->extension, ["txt", "plain", "html"], true)) {

                                    new HTMLArticle(
                                        $this->etc->templatePaths->$domPath,
                                        $this->dom->$parentPath->$domPath
                                    );
                                } elseif ($this->etc->extension === "js" && isset($etc["TEMPLATE"])) {

                                    $script = new HTMLElement("script", $this->dom->$parentPath->$domPath);
                                    $script->type = "module";
                                    $script->src = (string)$etc["TEMPLATE"];
                                }
                            } elseif (isset($etc["TEMPLATE"])) {

                                $script = new HTMLElement("script", $this->dom->$parentPath->$domPath);
                                $script->type = "module";
                                $script->src = (string)$etc["TEMPLATE"];
                            }
                        }

                        if (!is_array($attribute) && ($node instanceof HTMLSeries)) {

                            if ($value !== "ID") {

                                $node->$little = array_fill(
                                    0,
                                    count($node->elements),
                                    self::ensureUtf8((string)$attribute)
                                );

                            } elseif ($etcID === true) {

                                $getID = HTMLService::createID($tagName, count($node->elements));

                                foreach ($node->elements as $partNode) {
                                    $partNode->id = $getID->current();
                                    $getID->next();
                                }
                            } elseif (is_string($attribute) && isset($node->elements[0])) {

                                $node->elements[0]->$little = $attribute;
                            }
                        } elseif (is_array($attribute) && ($node instanceof HTMLSeries)) {

                            $counter = 1;

                            foreach ($node->elements as $partNode) {

                                if (isset($attribute[$counter])) {
                                    $partNode->$little = $attribute[$counter];
                                }
                                $counter++;
                            }
                        }

                        $getAttrValue->next();
                    }
                }
            }
        }
    }
}
