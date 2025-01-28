<?php

namespace toubeelib\infrastructure\PDO;

use toubeelib\core\repositoryInterfaces\AuthRepositoryInterface;
use toubeelib\core\dto\CredentialsDTO;
use toubeelib\core\dto\AuthDTO;
use PDO;
use toubeelib\core\domain\entities\user\User;

class PdoAuthRepository implements AuthRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * enregistre un utilisateur
     * @param CredentialsDTO $credentials
     * @param int $role
     */

    public function register(User $user): void
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO users (id, email, password, role) VALUES (:id, :email, :password, :role)');
            $stmt->execute([
                'id' => $user->getID(),
                'email' => $user->getEmail(),
                'password' => password_hash($user->getPassword(), PASSWORD_DEFAULT),
                'role' => $user->getRole()
            ]);
        } catch (\Exception $e) {
            throw new PdoAuthException('Erreur lors de l\'enregistrement de l\'utilisateur : ' . $e->getMessage());
        }
    }

    /**
     * Authentifie un utilisateur par son email
     * @param string $email
     * @return AuthDTO|null
     */

    public function login(string $email): ?AuthDTO
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->execute(['email' => $email]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new AuthDTO($row['id'], $row['email'], $row['password'], $row['role']);
            }
            return null;
        } catch (\Exception $e) {
            throw new PdoAuthException('Erreur lors de la connexion de l\'utilisateur : ' . $e->getMessage());
        }
    }

    /**
     * Récupère un utilisateur par son id
     * @param string $id
     * @return AuthDTO|null
     */
    public function getUserById(string $id): ?AuthDTO
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new AuthDTO($row['id'], $row['email'], $row['password'], $row['role']);
            }
            return null;
        } catch (\Exception $e) {
            throw new PdoAuthException('Erreur lors de la récupération de l\'utilisateur : ' . $e->getMessage());
        }
    }
}
