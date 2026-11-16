<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\HTTP {

    use Partarum\PartarumPHP\HTTP\Header;
    use Partarum\PartarumPHP\HTTP\Header\Entity;
    use Partarum\PartarumPHP\Security\Token\UUID;
    use Partarum\PartarumPHP\HTTP\Response\Channel;

    class APIResponse {

        public string $id;

        public Channel $channel;

        private bool $isHeader = false;

        public function __construct() {

            $this->id = (string)UUID::v4()->current();
            
            $this->channel = new Channel();
        }

        /*
         *  Generel and Response - Header !!!!
         *
         *  Entweder als Flag oder als String ( also selbsgeschriebenes Header - Field - Value )
         *
         *  oder Strings im Array
         */
        public function setHeader(string | array $value): void {

            $header = is_string($value) ? [$value] : $value;

            $this->channel->addHeader($header, Header::TYPE_GENEREL | Header::TYPE_RESPONSE);

            $this->isHeader = true;
        }

        public function setBody(mixed $body): void {

            $this->channel->addBody($body);  // Entity - Header
        }

        public function setStatus(int $status): void {
            http_response_code($status);
        }

        public function run(int $status = 200): void {

            if (!isset($this->channel->headerObject->contentType)) {

                $this->channel->addHeader([Entity::CONTENT_TYPE => "json"], Header::TYPE_ENTITY);

                $this->isHeader = true;
            }

            $this->channel->open($status);
        }

        public function getPostFromRequest(): null | string | array {
            return Data::fromPOST();
        }

        public function getHeaderFromRequest(?string $needle = null): mixed {
            return Data::fromHeader($needle, Data::REQUEST);
        }

        public function getRequestInfo(): void {

        }

        public function createUUID(): array {
            return ["v4" => UUID::v4()->current()];
        }
    }
}