<?php
namespace toubeelib\infrastructure\PDO;

use toubeelib\core\repositoryInterfaces\UserRepositoryInterface;
use toubeelib\core\dto\CredentialsDTO;
use toubeelib\core\dto\AuthDTO;
use PDO;

class PdoUserRepository implements UserRepositoryInterface {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): ?AuthDTO {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new AuthDTO($row['id'], $row['email'], $row['password'], $row['role']);
        }
        return null;
    }

    /**
     * enregistre un utilisateur
     * @param CredentialsDTO $credentials
     * @param int $role
     */

    public function save(CredentialsDTO $credentials, int $role): void {
        $stmt = $this->pdo->prepare('INSERT INTO users (email, password, role) VALUES (:email, :password, :role)');
        $stmt->execute([
            'email' => $credentials->getEmail(),
            'password' => password_hash($credentials->getPassword(), PASSWORD_DEFAULT),
            'role' => $role
        ]);
    }

    public function findById(string $id): ?AuthDTO
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new AuthDTO($row['id'], $row['email'], $row['password'], $row['role']);
        }
        return null;
    }
}