<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\HTTP {

    use ArrayIterator;
    use JsonException;
    use Partarum\PartarumPHP\HTTP\Header\Entity\Allow;
    use Partarum\PartarumPHP\HTTP\Request\REST;
    use Partarum\PartarumPHP\Security\Token\UUID;

    class APIRequest {

        /*
         *  Das REST - Object
         */
        public REST $rest;

        /*
         *  Adresse der API
         */
        public string $url = "";
        
        public string $param = "?";

        /*
         *  HTTP - Methode | POST, GET, PUT, DELETE, UPDATE
         */
        public string $method = Allow::GET;

        /*
         *  HTTP - Authentifizierung
         */
        public array $auth = [];

        /*
         *  Content - Type
         */
        public string $contentType = REST::JSON;

        public mixed $content = null;

        public mixed $result = null;
        
        public string $id;

        private string $params = "?";

        public function __construct() {

            $this->id = (string)UUID::v4()->current();
            
            $this->rest = new REST();
        }
        
        public function setURL(string $url): void {

            $this->url = $url;
        }
        
        public function setParams(array $params): void {
            
            $this->params = "?";

            $iterator = new ArrayIterator($params);
            
            while ($iterator->valid()) {
                
                $this->params .= $iterator->key() . "=" . $iterator->current();
                
                $iterator->next();
                
                if ($iterator->valid()) {
                    $this->params .= "&";
                }
            }
        }

        public function getParams(): string {
            return $this->params;
        }

        public function setMethod(string $method): void {
            $this->method = $method;
        }

        public function setBaseHeader(): void {

        }

        public function setPersonalHeader(array $header): void {

        }

        public function setBody(mixed $content, int $flag = 0): void {
            $this->content = $content;
        }

        // erwarte einen oder mehrere bestimmte MIME - Typen
        public function expectContentType(string $type): void {

        }

        // erwarte bestimmte Bedingungen vom Header
        public function expectStrictHeader(string | array $param): void {

        }

        /**
         * @throws JsonException
         */
        public function run(): mixed {

            if ($this->method === Allow::GET) {
                
                $this->rest->setURL(strlen($this->params) > 1 ? $this->url . $this->params : $this->url);

            } else {
                
                $this->rest->setURL($this->url);
            }

            $this->rest->setMethod($this->method);

            if ($this->method !== Allow::GET) {
                
                // baseHeader und personalHeader auswerten
                $this->rest->setHeader([
                    //"auth" => $this->auth ?? NULL,
                    "contentType" => $this->contentType
                ]);

                $this->rest->setContent($this->content);

            }

            $this->result = $this->rest->exec();

            $this->rest->close();

            if (!is_string($this->result) || trim($this->result) === "") {
                return null;
            }

            // ! muss noch auf den Content geprüft werden - es soll kein JSON - String zurückgegeben werden!!!
            return json_decode($this->result, false, 512, JSON_THROW_ON_ERROR);
        }

        /**
         * @return mixed
         */
        public function getResult(): mixed {
            return $this->result;
        }
        
        public function getBaseHeader(): void {

        }
    }
}