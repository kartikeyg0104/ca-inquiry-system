<?php

class Admin {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email) {
        $stmt = $this->pdo->prepare("SELECT id, name, email, password FROM admins WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verifyPassword(string $plaintext, string $hash): bool {
        return password_verify($plaintext, $hash);
    }

    public function updatePassword(int $id, string $newPassword): bool {
        $hash = password_hash($newPassword, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 1
        ]);
        $stmt = $this->pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }

    public function createAdmin(string $name, string $email, string $password): bool {
        $hash = password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 1
        ]);

        try {
            $stmt = $this->pdo->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
            return $stmt->execute([$name, $email, $hash]);
        } catch (PDOException $e) {
            // SQLSTATE 23505 is PostgreSQL's unique_violation code
            if ($e->getCode() == '23505') {
                return false; 
            }
            throw $e; 
        }
    }
}
