<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\HTTP {

    use ArgumentCountError;

    class CookieObject {

        private string $name = "";

        private string $value = "";

        private int $expire = 0;

        private string $path = "/";

        private string $domain = "";

        private bool $secure = false;

        private bool $httponly = false;

        private string $samesite = Cookie::LAX;

        public function __construct(mixed ...$arg) {

            if (count($arg) === 1 && is_array($arg[0])) {

                /*
                 *  $arg[0] ein Array mit den Attributen
                 */
                $this->setProps($arg[0]);

            } elseif (count($arg) === 8) {

                /*
                 *  8 Einzelargumente mit den Attributen
                 */
                $this->setProps($arg);

            } else {

                /*
                 *  Falsche Anzahl - die Übergabe an den Constructor ist klar definiert
                 */
                throw new ArgumentCountError("You need one array argument with all Cookie - attributes or 8 attributes as arguments");
            }
        }

        private function setProps(array $arg): void {

            $this->name = (string)($arg["name"] ?? $arg[0] ?? "");
            $this->value = (string)($arg["value"] ?? $arg[1] ?? "");
            $this->expire = (int)($arg["expire"] ?? $arg["lifetime"] ?? $arg[2] ?? 0);
            $this->path = (string)($arg["path"] ?? $arg[3] ?? "/");
            $this->domain = (string)($arg["domain"] ?? $arg[4] ?? "");
            $this->secure = (bool)($arg["secure"] ?? $arg[5] ?? false);
            $this->httponly = (bool)($arg["httponly"] ?? $arg[6] ?? false);
            $this->samesite = (string)($arg["samesite"] ?? $arg[7] ?? Cookie::LAX);
        }

        public function setName(string $name): void {

            $this->name = $name;
        }

        public function setValue(string $value): void {

            $this->value = $value;
        }

        public function setExpire(int $expire): void {

            $this->expire = $expire;
        }

        public function setPath(string $path): void {

            $this->path = $path;
        }

        public function setDomain(string $domain): void {

            $this->domain = $domain;
        }

        public function setSecure(bool $secure): void {

            $this->secure = $secure;
        }

        public function setHttponly(bool $httponly): void {

            $this->httponly = $httponly;
        }

        public function setSamesite(string $samesite): void {

            $this->samesite = $samesite;
        }

        public function getName(): string {

            return $this->name;
        }

        public function getValue(): string {

            return $this->value;
        }

        public function getExpire(): int {

            return $this->expire;
        }

        public function getPath(): string {

            return $this->path;
        }

        public function getDomain(): string {

            return $this->domain;
        }

        public function getSecure(): bool {

            return $this->secure;
        }

        public function getHttponly(): bool {

            return $this->httponly;
        }

        public function getSamesite(): string {

            return $this->samesite;
        }
    }
}