<?php

namespace toubeelib\core\repositoryInterfaces;

use toubeelib\core\domain\entities\user\User;
use toubeelib\core\dto\AuthDTO;

interface AuthRepositoryInterface {
    public function register (User $user): void; // enregistre un utilisateur
    public function login(string $email): ?AuthDTO; // Authentifie un utilisateur par son email
    public function getUserById(string $id): ?AuthDTO; // Récupère un utilisateur par son id
}