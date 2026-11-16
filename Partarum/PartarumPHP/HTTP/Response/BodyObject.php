<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\HTTP\Response {

    use ArrayIterator;
    use JsonException;

    class BodyObject {

        public ArrayIterator $cache;

        public string $type = "JSON";

        private array $outputJSON = [];

        private string $outputTEXT = "";

        public function __construct() {

            $this->cache = new ArrayIterator([]);
        }

        public function addBody(mixed $body): void {

            $this->cache->append($body);
        }

        public function setType(string $contentType): void {

            $this->type = match (strtolower($contentType)) {
                "json" => "JSON",
                "text", "html", "xml" => "Text",
                default => $contentType
            };
        }

        public function setBody(): void {

            $method = "add_" . $this->type;

            $this->cache->rewind();

            while ($this->cache->valid()) {

                if (method_exists($this, $method)) {
                    $this->$method($this->cache->current());
                }

                $this->cache->next();
            }

            $this->sendOutput();
        }

        private function sendOutput(): void {

            $method = "send_" . $this->type;

            if (method_exists($this, $method)) {
                $this->$method();
            }
        }

        private function add_JSON(mixed $content): void {

            $this->outputJSON[] = $content;
        }

        private function send_JSON(): void {

            try {

                echo json_encode($this->outputJSON, JSON_THROW_ON_ERROR);

            } catch (JsonException $je) {
                error_log("[Partarum][BodyObject] JSON encode failed: " . $je->getMessage());
                echo '{"error": "Output not produce"}';
            }
        }

        private function add_Text(mixed $content): void {

            $this->outputTEXT .= (string)$content . PHP_EOL;
        }

        private function send_Text(): void {

            echo $this->outputTEXT;
        }
    }
}