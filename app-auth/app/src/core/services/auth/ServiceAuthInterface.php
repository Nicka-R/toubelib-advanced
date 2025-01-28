<?php

namespace toubeelib\core\services\auth;

use toubeelib\core\dto\AuthDTO;
use toubeelib\core\dto\CredentialsDTO;
use app\providers\auth\Token;

interface ServiceAuthInterface
{   
    public function register(CredentialsDTO $credentials, int $role): void; // Methode pour enregistrer un utilisateur
    public function login(CredentialsDTO $credentials): AuthDTO; // Methode pour authentifier un utilisateur
    // public function refresh(Token $token): AuthDTO;
    // public function getSignedInUser(Token $token): AuthDTO;
}