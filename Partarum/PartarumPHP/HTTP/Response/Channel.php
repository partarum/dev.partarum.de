<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\HTTP\Response {

    use Partarum\PartarumPHP\HTTP\Header;
    use Partarum\PartarumPHP\HTTP\Header\Entity;
    use Partarum\PartarumPHP\HTTP\HeaderObject;
    use Partarum\PartarumPHP\HTTP\Response\BodyObject;

    class Channel {

        public HeaderObject $headerObject;

        public BodyObject $bodyObject;

        public function __construct() {

            $this->headerObject = new HeaderObject();

            $this->bodyObject = new BodyObject();
        }

        /*
         *  immer Generel oder Response - Header
         */

        public function addHeader(array $value, int $type): void {

            switch ($type) {

                case Header::TYPE_GENEREL | Header::TYPE_RESPONSE:
                case Header::TYPE_ENTITY:

                    $this->headerObject->addHeader($value);
                    break;
            }
        }

        /**
         * @param mixed $body
         */
        public function addBody(mixed $body): void {

            $this->bodyObject->addBody($body);
        }

        public function open(int $status = 200): void {

            http_response_code($status);

            if (!isset($this->headerObject->contentType)) {

                $this->headerObject->addHeader([Entity::CONTENT_TYPE => "json"]);
            }

            $this->headerObject->setHeader();

            $this->bodyObject->setType((string)$this->headerObject->contentType);

            $this->bodyObject->setBody();
        }

    }
}