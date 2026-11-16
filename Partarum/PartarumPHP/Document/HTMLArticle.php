<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Document {

    use DOMElement;
    use Generator;
    use SplFileObject;
    use stdClass;

    class HTMLArticle {

        public SplFileObject $file;

        public HTMLElement $articleDom;

        public array $cacheOpenBlockTagNodes = [];

        public array $cacheInlineTagNodes = [];

        public array $cacheOpenBlockTagNames = [];

        public bool $list = false;

        public array $liCache = [];

        public stdClass $textObject;

        public array $cache = [];

        public int $cacheParentTag = 0;

        private static mixed $trash = null;

        public array $inlineTags = [
            "big", "b", "i", "small", "tt", "abbr",
            "acronym", "cite", "code", "dfn", "em", "kbd", "strong",
            "samp", "var", "a", "bdo", "br", "img", "map", "object", "q",
            "span", "script", "sub", "sup", "button", "input", "label", "select", "textarea"
        ];

        public array $blockTags = [
            "address", "article", "aside", "blockquote", "details",
            "dialog", "dd", "div", "dl", "dt", "fieldset", "figcaption", "figure",
            "footer", "form", "h1", "h2", "h3", "h4", "h5", "h6", "header", "hgroup",
            "hr", "li", "main", "nav", "ol", "p", "pre", "section", "table", "ul"
        ];

        public array $ownBlockTags = ["list"];

        public function __construct(string $filePath, object $parentNode) {

            $this->setBase($parentNode);

            $this->textObject = new stdClass();

            $this->getFile($filePath);

            $counter = 1;

            foreach ($this->file as $line) {
                if (!is_string($line)) {
                    continue;
                }

                $cleaned = $this->cW($line);
                if ($cleaned === "") {
                    continue;
                }

                $resultLine = $this->getType($cleaned);
                $current = $resultLine->current();

                if (is_array($current)) {
                    $this->setTextObject($current, $counter);
                }

                $counter++;
            }
        }

        protected static function ensureUtf8(string $text): string {

            if (mb_check_encoding($text, "UTF-8")) {
                return $text;
            }

            return mb_convert_encoding($text, "UTF-8", "ISO-8859-1");
        }

        public function setBase(object $parentNode): void {

            $target = ($parentNode instanceof DOMElement)
                ? $parentNode
                : ($parentNode->elements[0] ?? null);

            $this->articleDom = new HTMLElement("article", $target);
            $this->articleDom->align = "center";
        }

        private function setList(): Generator {

            $this->list = true;

            yield "ul";
        }

        public function startBlockTag(array $line): void {

            $attributeStartBlockTag = "class";

            $tagName = ($line[1] === "list") ? (string)$this->setList()->current() : (string)$line[1];

            $countNodes = count($this->cacheOpenBlockTagNodes);
            $countNames = count($this->cacheOpenBlockTagNames);

            if ($countNames === 0) {

                $node = new HTMLElement($tagName, $this->articleDom);

                $this->cacheParentTag = $countNodes;

            } else {

                $parent = (($countNames === 1) && ($countNodes >= 1))
                    ? $this->cacheOpenBlockTagNodes[$this->cacheParentTag]
                    : (($countNames > 1) ? ($this->cacheOpenBlockTagNodes[$this->cacheParentTag + 1] ?? end($this->cacheOpenBlockTagNodes)) : false);

                $node = new HTMLElement($tagName, $parent);
            }

            $this->cacheOpenBlockTagNodes[] = $node;
            $this->cacheOpenBlockTagNames[] = $line[1];

            if (isset($line[2])) {
                $this->cacheOpenBlockTagNodes[$countNodes]->$attributeStartBlockTag = $line[2];
            }
        }

        public function createTextElement(array $line): void {

            $liCacheLength = count($this->liCache);
            $textContent = (string)($line[1] ?? "");

            $tagName = ($this->list === false)
                ? "p"
                : (str_contains($textContent, "+") ? "li" : "p");

            $targetParent = !empty($this->cacheOpenBlockTagNodes)
                ? end($this->cacheOpenBlockTagNodes)
                : $this->articleDom;

            $parent = (($tagName === "p") && ($liCacheLength === 0))
                ? $targetParent
                : ((($tagName === "li") && ($liCacheLength >= 0))
                    ? $targetParent
                    : ((($tagName === "p") && ($liCacheLength === 1)) ? end($this->liCache) : $targetParent));

            $textNode = new HTMLElement($tagName, $parent);

            if (($tagName === "li") && ($liCacheLength === 0)) {

                $blockText = new HTMLElement("p", $textNode);
                $blockText->text = self::ensureUtf8(ltrim($textContent, "+"));
                $this->liCache[] = $textNode;

            } elseif (($tagName === "li") && ($liCacheLength > 0)) {

                $blockText = new HTMLElement("p", $textNode);
                $blockText->text = self::ensureUtf8(ltrim($textContent, "+"));
                array_pop($this->liCache);

            } elseif ($tagName === "p") {

                $textNode->text = self::ensureUtf8($textContent);
            }
        }

        public function setTextObject(array $line, int $counter): void {

            $type = $line[0] ?? "";

            if ($type === "startBlockTag") {

                $this->startBlockTag($line);

            } elseif ($type === "endBlockTag") {

                if (str_replace("/", "", (string)($line[1] ?? "")) === end($this->cacheOpenBlockTagNames)) {

                    self::$trash = array_pop($this->cacheOpenBlockTagNames);

                    if ($line[1] === "/ul") {
                        $this->list = false;
                    }
                }
            } elseif ($type === "text" || $type === "smallText") {

                $this->createTextElement($line);

            } elseif ($type === "inline") {

                if (is_array($line[1] ?? null) && is_array($line[2] ?? null) && count($line[1]) === (count($line[2]) - 1)) {

                    $restText = $line[2];
                    $result = $line[1];
                    $targetParent = !empty($this->cacheOpenBlockTagNodes)
                        ? end($this->cacheOpenBlockTagNodes)
                        : $this->articleDom;

                    foreach ($restText as $key => $textpart) {

                        if (strlen((string)$textpart) > 0) {
                            $inlineTextNode = new HTMLElement("p", $targetParent);
                            $inlineTextNode->text = self::ensureUtf8((string)$textpart);
                        }

                        if (isset($result[$key])) {

                            $inlineEl = new HTMLElement((string)$result[$key][2], $targetParent);
                            $inlineEl->text = self::ensureUtf8(strip_tags((string)$result[$key][0]));
                            $this->cacheInlineTagNodes[] = $inlineEl;

                            if (count($result[$key]) > 5) {

                                preg_match_all("/(.*?)(\"[\w]+\")/", (string)$result[$key][3], $attr, PREG_SET_ORDER);

                                if (isset($attr[0][1], $attr[0][2])) {
                                    $attribute = trim(str_replace("=", "", $attr[0][1]));
                                    $attributeValue = str_replace("\"", "", $attr[0][2]);
                                    $inlineEl->$attribute = $attributeValue;
                                }
                            }
                        }
                    }
                }
            }
        }

        public function getFile(string $filePath): void {

            $this->file = new SplFileObject($filePath);
        }

        public function cW(string $string): string {

            return trim((string)preg_replace('/\s+/', ' ', $string));
        }

        public function getMarkupTag(string $string): Generator {

            yield match ($string) {
                "<->" => "header",
                "</->" => "/header",
                "<-->" => "section",
                "</-->" => "/section",
                "<--->" => "footer",
                "</--->" => "/footer",
                default => false
            };
        }

        public function getBlockTag(string $line, string $place): Generator {

            $cleanString = "";

            if ($place === "start") {
                $cleanString = str_replace(["<", ">"], "", $line);
            } elseif ($place === "end") {
                $cleanString = str_replace(["</", ">"], "", $line);
            }

            if (in_array($cleanString, $this->blockTags, true) || in_array($cleanString, $this->ownBlockTags, true)) {
                if ($place === "start") {
                    yield $cleanString;
                } elseif ($place === "end") {
                    yield "/" . $cleanString;
                }
            }
        }

        public function getType(string $line): Generator {

            $length = strlen($line);

            if ($length <= 10) {

                if (str_contains($line, "<") && str_contains($line, ">") && !str_contains($line, "</")) {

                    $tagNameStart = $this->getMarkupTag($line)->current();

                    if ($tagNameStart === false) {
                        $tagNameStart = $this->getBlockTag($line, "start")->current();
                    }

                    yield ["startBlockTag", $tagNameStart];

                } elseif (!str_contains($line, "<") && !str_contains($line, ">") && !str_contains($line, "</")) {

                    yield ["smallText", $line];
                }

                if (str_contains($line, "</")) {

                    $tagNameEnd = $this->getMarkupTag($line)->current();

                    if ($tagNameEnd === false) {
                        $tagNameEnd = $this->getBlockTag($line, "end")->current();
                    }

                    yield ["endBlockTag", $tagNameEnd];
                }

            } elseif (!str_contains($line, "<") && !str_contains($line, ">") && !str_contains($line, "</")) {

                yield ["text", $line];

            } elseif (str_contains($line, "<") && str_contains($line, "[")) {

                preg_match("/(<.*?>)(\[.*?])/", $line, $styleArray);

                $tagPart = $styleArray[1] ?? "";
                $attrPart = $styleArray[2] ?? "";

                $tagNameStart = $this->getMarkupTag($tagPart)->current();

                if ($tagNameStart === false) {
                    $tagNameStart = $this->getBlockTag($tagPart, "start")->current();
                }

                $attribute = str_replace(["[", "]"], "", $attrPart);

                yield ["startBlockTag", $tagNameStart, $attribute];

            } else {

                $stringOTag = "/(<([\w]++)[^>](.*?)>)(.*?)(<\/\\2>)/";
                $stringTagWithoutAttribute = "/(<([\w]++)[^>]*>)(.*?)(<\/\\2>)/";

                $result = [];

                preg_match_all($stringOTag, $line, $inlineWithAttribute, PREG_SET_ORDER);

                if (count($inlineWithAttribute) === 0) {

                    preg_match_all($stringTagWithoutAttribute, $line, $inline, PREG_SET_ORDER);
                    $result = $inline;

                } else {

                    preg_match_all($stringTagWithoutAttribute, $line, $inline, PREG_SET_ORDER);

                    if (count($inlineWithAttribute) === count($inline)) {
                        $result = $inlineWithAttribute;
                    } else {
                        foreach ($inlineWithAttribute as $key => $value) {
                            $result[] = $value;

                            foreach ($inline as $inlineKey => $inlineArray) {
                                if ($value[0] !== $inlineArray[0]) {
                                    if ($key !== $inlineKey) {
                                        $result[] = $inlineArray;
                                    } else {
                                        array_unshift($result, $inlineArray);
                                    }
                                }
                            }
                        }
                    }
                }

                $splitPatternTags = "/(<.*?>.*?<\/.*?>)/";
                $restText = preg_split($splitPatternTags, $line) ?: [];

                yield ["inline", $result, $restText];
            }
        }
    }
}