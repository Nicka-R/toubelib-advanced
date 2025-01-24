<?php

namespace toubeelib\core\services\auth;

use toubeelib\core\dto\AuthDTO;
use toubeelib\core\dto\CredentialsDTO;

interface AuthServiceInterface
{
    public function authenticate(CredentialsDTO $authDTO): AuthDTO;
    public function register(CredentialsDTO $credentials, int $role): void;
    public function getUserById(string $id): AuthDTO;
}