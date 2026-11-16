<?php
declare(strict_types=1);
/*
 *           Copyright 2020 © Alexander Bombis. All rights reserved.
 *           Developed by Alexander Bombis.
 *           Email: email@alexander-bombis.de
 *
 *           The following code was created based on the template for the website http://cordes-software.de.
 *           This may also be used in full by the person or business
 *           representing the domain "cordes-software.de"
 *           and also modified for their use.
 */
namespace Partarum\PartarumPHP\HTTP {

    use Partarum\PartarumPHP\HTTP\APIRequest;
    use Partarum\PartarumPHP\HTTP\APIResponse;
    use Partarum\PartarumPHP\HTTP\Data;
    use Partarum\PartarumPHP\HTTP\Header;
    use Partarum\PartarumPHP\HTTP\Header\Entity\Allow;
    use Partarum\PartarumPHP\HTTP\Header\Entity;
    use Partarum\PartarumPHP\HTTP\Request\REST;
    use Partarum\PartarumPHP\Security\Token\UUID;

    class API extends Header {

        public array $requestCache = [];

        public array $responseCache = [];

        public ?APIRequest $request = null;

        public ?APIResponse $response = null;

        public const REQUEST = Data::REQUEST;
        
        public const RESPONSE = Data::RESPONSE;
        
        public const METHOD_GET = Allow::GET;

        public const METHOD_POST = Allow::POST;

        public mixed $data = null;

        public function __construct() {

        }

        private function getRequestData(): void {

            $this->data = REST::getRequestData();
        }

        public function createRequest(null | string $url = null): APIRequest {

            $request = new APIRequest();
            
            if (isset($url)) {
                $request->setURL($url);
            }

            $this->request = $request;
            $this->requestCache[$request->id] = $request;

            return $request;
        }

        public function setRequest(): APIRequest {

            return $this->createRequest();
        }

        public function addRequest(APIRequest $request): void {

            $this->request = $request;
            $this->requestCache[$request->id] = $request;
        }

        public function getRequest(?string $id = null): null | APIRequest | array {

            if ($id !== null) {
                return $this->requestCache[$id] ?? null;
            }

            return $this->request ?? $this->requestCache;
        }

        public function createResponse(): APIResponse {

            $this->response = new APIResponse();

            $this->responseCache[$this->response->id] = $this->response;

            return $this->response;
        }

        public function createJSONResponse(mixed $body = null): APIResponse {

            $response = $this->createResponse();

            $response->setHeader([Entity::CONTENT_TYPE => "json"]);

            if (isset($body)) {
                $response->setBody($body);
            }

            return $response;
        }

        public function setResponse(): APIResponse {

            return $this->createResponse();
        }
        
        public function getData(): Data {
            
            return new Data();
        }

        public function fromGET(null | string $needle = null): null | string | array {

            return Data::fromGET($needle);
        }

        public function fromPOST(null | string $needle = null): null | string | array {

            return Data::fromPOST($needle);
        }
        
        public function fromHeader(?string $key = null, ?int $flag = null): mixed {

            return Data::fromHeader($key, $flag);
        }

        public function fromBearerToken(): ?string {

            $authHeader = $this->fromHeader("Authorization", self::REQUEST);

            if (!is_string($authHeader) || $authHeader === "") {
                $authHeader = $_SERVER["HTTP_AUTHORIZATION"] ?? $_SERVER["REDIRECT_HTTP_AUTHORIZATION"] ?? null;
            }

            if (!is_string($authHeader) || $authHeader === "") {
                return null;
            }

            if (preg_match('/^\s*Bearer\s+(.+?)\s*$/i', $authHeader, $matches) === 1) {
                return $matches[1];
            }

            return null;
        }

        public function createUUID(): array {

            return ["v4" => UUID::v4()->current()];
        }

        public function getRequestScheme(): string {

            $requestScheme = strtolower((string)($_SERVER["REQUEST_SCHEME"] ?? ""));

            if ($requestScheme === "http" || $requestScheme === "https") {
                return $requestScheme;
            }

            $https = strtolower((string)($_SERVER["HTTPS"] ?? ""));

            if ($https !== "" && $https !== "off" && $https !== "0") {
                return "https";
            }

            $port = (int)($_SERVER["SERVER_PORT"] ?? 443);

            if ($port === 80) {
                return "http";
            }

            return "https";
        }

        public function redirectOutside(string $url): void {

            if (!headers_sent()) {
                if (str_starts_with($url, "http://") || str_starts_with($url, "https://")) {
                    header("Location: {$url}");
                } else {
                    $protocol = $this->getRequestScheme();
                    header("Location: {$protocol}://{$url}");
                }
            }
        }

        public function redirectInside(string $path): void {

            if (!headers_sent()) {
                header("Location: {$path}");
            }
        }
    }
}

