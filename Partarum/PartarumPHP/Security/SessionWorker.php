<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Security {

    use Partarum\PartarumPHP\Traits\Security\FeatSessionWorker;
    use SessionHandlerInterface;

    class SessionWorker implements SessionHandlerInterface
    {
        use FeatSessionWorker;

        public bool $sessionOpen = false;

        public function __construct(bool $setSession = false) {

            /*
             * session_status() == 0 = PHP_SESSION_DISABLED
             * session_status() == 1 = PHP_SESSION_NONE => session_start()
             * session_status() == 2 = PHP_SESSION_ACTIVE
             */

            if (($setSession === true) && (!isset($_COOKIE["PHPSESSID"])) && (session_status() === PHP_SESSION_NONE) && (!headers_sent())) {

                $this->setINI();

                session_set_save_handler($this, true);

                if (!session_start()) {

                    session_abort();

                } else {

                    $_SESSION["id"] = "true";

                    session_write_close();

                    $this->sessionOpen = true;
                }
            }
        }
    }
}