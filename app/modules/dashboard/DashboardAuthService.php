<?php
declare(strict_types=1);

namespace app\modules\dashboard;

use Partarum\PartarumPHP\HTTP\API;
use Partarum\PartarumPHP\Security\Token\JWT;

class DashboardAuthService {

    private API $api;
    private string $domain;

    public function __construct(?API $api = null) {

        $this->api = $api ?? new API();
        $this->domain = (string)($_ENV["DOMAIN"] ?? $_SERVER["SERVER_NAME"] ?? "localhost");
    }

    /**
     * Setzt die bestehende Session zurück und stellt das "beer"-Handshake-Cookie für den Login auf /dashboard aus.
     */
    public function prepareLoginSession(): void {

        $this->startSessionIfNeeded();

        $_SESSION = [];

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        if (!headers_sent()) {
            setcookie("beer", (string)time(), [
                "expires" => time() + 120,
                "path" => "/dashboard",
                "domain" => $this->domain,
                "secure" => true,
                "httponly" => true,
                "samesite" => "strict"
            ]);
        }
    }

    /**
     * Prüft das "beer"-Handshake-Cookie sowie User/Passwort gegen $_ENV und stellt bei Erfolg Session + "bearer"-Cookie aus.
     */
    public function authenticate(): bool {

        if (!isset($_COOKIE["beer"])) {
            return false;
        }

        $user = $this->api->fromPOST("user");
        $password = $this->api->fromPOST("password");

        if (!is_string($user) || !is_string($password) || $user === "" || $password === "") {
            return false;
        }

        $expectedUser = (string)($_ENV["AUTH_USER"] ?? "");
        $expectedPassword = (string)($_ENV["AUTH_PASSWORD"] ?? "");

        if ($expectedUser === "" || $expectedPassword === "") {
            return false;
        }

        if (!hash_equals($expectedUser, $user) || !hash_equals($expectedPassword, $password)) {
            return false;
        }

        $timeNow = time();
        $jwt = new JWT();
        $jwt->setSecretKey((string)($_ENV["JWT_SECRET_KEY"] ?? ""));
        $jwt->setPayload([
            "user" => $user,
            "time" => $timeNow
        ]);

        $bearer = $jwt->getToken();

        $this->startSessionIfNeeded();

        $_SESSION["bearer"] = $bearer;
        $_SESSION["arrival"] = $timeNow;

        if (!headers_sent()) {
            setcookie("bearer", (string)$timeNow, [
                "expires" => $timeNow + 120,
                "path" => "/dashboardPage",
                "domain" => $this->domain,
                "secure" => true,
                "httponly" => true,
                "samesite" => "strict"
            ]);
        }

        return true;
    }

    /**
     * Prüft auf /dashboardPage, ob Session ("arrival", "bearer") und Cookie ("bearer") übereinstimmen,
     * und verlängert das "arrival"-Cookie.
     */
    public function verifyWorkspaceAccess(): bool {

        $this->startSessionIfNeeded();

        if (!isset($_SESSION["arrival"], $_SESSION["bearer"], $_COOKIE["bearer"])) {
            return false;
        }

        $arrival = (string)$_SESSION["arrival"];
        $bearerCookie = (string)$_COOKIE["bearer"];

        if (!hash_equals($arrival, $bearerCookie)) {
            return false;
        }

        if (!headers_sent()) {
            setcookie("arrival", (string)time(), [
                "expires" => time() + 3600,
                "path" => "/dashboardPage",
                "domain" => $this->domain,
                "secure" => true,
                "httponly" => true,
                "samesite" => "strict"
            ]);
        }

        return true;
    }

    public function redirectTo(string $path): void {

        $this->api->redirectInside($path);
    }

    private function startSessionIfNeeded(): void {

        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
    }
}
