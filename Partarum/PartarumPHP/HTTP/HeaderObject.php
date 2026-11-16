<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\HTTP {

    use ArrayIterator;
    use Partarum\PartarumPHP\HTTP\Header\Entity\ContentType;
    use Partarum\PartarumPHP\HTTP\Header\HeaderField;

    class HeaderObject {

        public ArrayIterator $cache;

        public string | null $contentType = null;

        public function __construct() {

            $this->cache = new ArrayIterator([]);
        }

        public function addHeader(array $header): void {

            foreach ($header as $key => $value) {

                /*
                 *      Prüfen ob es eine Stringangabe ist oder ein String der eine Klasse angibt
                 *
                 *      -   ["Content-Type: application/json"]
                 *
                 *      -   [Entity::CONTENT_TYPE => "json"]
                 */

                if (is_int($key)) {

                    $this->cache->append((string)$value);

                } elseif (class_exists($key)) {

                    $impl = class_implements($key);

                    if (is_array($impl) && in_array(HeaderField::class, $impl, true)) {

                        $c = [$key, "getOutput"]($value);

                        if (($key === ContentType::class) && (!isset($this->contentType))) {

                            $this->contentType = (string)$value;
                        }

                        if (is_string($c) && $c !== "") {
                            $this->cache->append($c);
                        }
                    }
                }
            }
        }

        public function setHeader(): void {

            $this->cache->rewind();

            while ($this->cache->valid()) {

                $headerLine = (string)$this->cache->current();

                if ($headerLine !== "" && !headers_sent()) {
                    header($headerLine);
                }

                $this->cache->next();
            }
        }
    }
}