<?php

namespace toubeelib\core\repositoryInterfaces;

use toubeelib\core\dto\AuthDTO;
use toubeelib\core\dto\CredentialsDTO;

interface UserRepositoryInterface {
    public function findByEmail(string $email): ?AuthDTO;
    public function save (CredentialsDTO $credentials, int $role): void;
    public function findById(string $id): ?AuthDTO;
}