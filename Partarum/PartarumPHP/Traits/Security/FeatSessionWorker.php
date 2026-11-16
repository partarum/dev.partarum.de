<?php
declare(strict_types=1);

namespace Partarum\PartarumPHP\Traits\Security {

    use mysqli;
    use Throwable;

    trait FeatSessionWorker
    {

        protected ?mysqli $sessionPath = null;

        public function setINI(): void
        {
            if (!headers_sent() && session_status() === PHP_SESSION_NONE) {
                ini_set("session.use_only_cookies", "1");
                ini_set("session.use_trans_sid", "0");
                ini_set("session.gc_maxlifetime", "3600");
                ini_set("session.gc_probability", "1");
                ini_set("session.gc_divisor", "100");
            }
        }

        public function open(string $path, string $name): bool {

            $host = (string)($_ENV["SESSION_DB_HOST"] ?? $_ENV["DB_HOST"] ?? "localhost");
            $user = (string)($_ENV["SESSION_DB_USER"] ?? $_ENV["DB_USER"] ?? "bombisa");
            $pass = (string)($_ENV["SESSION_DB_PASS"] ?? $_ENV["DB_PASS"] ?? "");
            $db   = (string)($_ENV["SESSION_DB_NAME"] ?? $_ENV["DB_NAME"] ?? "bombisa");

            try {
                $conn = @new mysqli($host, $user, $pass, $db);

                if ($conn->connect_errno) {
                    $this->sessionPath = null;
                    return false;
                }

                $this->sessionPath = $conn;
                return true;
            } catch (Throwable $e) {
                error_log("[Partarum][FeatSessionWorker] DB connection failed: " . $e->getMessage());
                $this->sessionPath = null;
                return false;
            }
        }

        public function close(): bool {

            if ($this->sessionPath instanceof mysqli) {
                $closed = $this->sessionPath->close();
                $this->sessionPath = null;
                return $closed;
            }

            return true;
        }

        public function write(string $id, string $data): bool {

            if (!($this->sessionPath instanceof mysqli)) {
                return false;
            }

            $stmt = $this->sessionPath->prepare("REPLACE INTO sessions (sess_id, sess_time, sess_value) VALUES (?, ?, ?)");

            if ($stmt === false) {
                return false;
            }

            $now = time();
            $stmt->bind_param("sis", $id, $now, $data);
            $result = $stmt->execute();
            $stmt->close();

            return $result;
        }

        public function read(string $id): string|false {

            if (!($this->sessionPath instanceof mysqli)) {
                return "";
            }

            $stmt = $this->sessionPath->prepare("SELECT sess_value FROM sessions WHERE sess_id = ?");

            if ($stmt === false) {
                return "";
            }

            $stmt->bind_param("s", $id);

            if (!$stmt->execute()) {
                $stmt->close();
                return "";
            }

            $result = $stmt->get_result();
            $row = ($result !== false) ? $result->fetch_assoc() : null;
            $stmt->close();

            return isset($row["sess_value"]) ? (string)$row["sess_value"] : "";
        }

        public function destroy(string $id): bool
        {
            if (!($this->sessionPath instanceof mysqli)) {
                return false;
            }

            $stmt = $this->sessionPath->prepare("DELETE FROM sessions WHERE sess_id = ?");

            if ($stmt === false) {
                return false;
            }

            $stmt->bind_param("s", $id);
            $result = $stmt->execute();
            $stmt->close();

            return $result;
        }

        public function gc(int $max_lifetime): int|false
        {
            if (!($this->sessionPath instanceof mysqli)) {
                return false;
            }

            $sessLife = time() - $max_lifetime;
            $stmt = $this->sessionPath->prepare("DELETE FROM sessions WHERE sess_time < ?");

            if ($stmt === false) {
                return false;
            }

            $stmt->bind_param("i", $sessLife);

            if (!$stmt->execute()) {
                $stmt->close();
                return false;
            }

            $affected = (int)$stmt->affected_rows;
            $stmt->close();

            return $affected;
        }

    }
}
