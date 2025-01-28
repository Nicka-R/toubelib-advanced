<?php

namespace toubeelib\core\repositoryInterfaces;

use toubeelib\core\domain\entities\user\User;
use toubeelib\core\dto\AuthDTO;

interface AuthRepositoryInterface {
    public function register (User $user): void;

    public function findByEmail(string $email): ?AuthDTO;    
    public function findById(string $id): ?AuthDTO;
}