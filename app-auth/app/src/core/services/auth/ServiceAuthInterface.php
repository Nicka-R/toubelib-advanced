<?php

namespace toubeelib\core\services\auth;

use toubeelib\core\dto\AuthDTO;
use toubeelib\core\dto\CredentialsDTO;
use app\providers\auth\Token;

interface ServiceAuthInterface
{   
    public function register(CredentialsDTO $credentials, int $role): void;

    // public function login(CredentialsDTO $authDTO): AuthDTO;
    // public function refresh(Token $token): AuthDTO;
    // public function getSignedInUser(Token $token): AuthDTO;
}