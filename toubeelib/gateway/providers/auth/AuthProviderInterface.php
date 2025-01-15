<?php
namespace app\providers\auth;
use toubeelib\core\dto\CredentialsDTO;
use toubeelib\core\dto\AuthDTO;
use app\providers\auth\Token;

interface AuthProviderInterface {
    public function register(CredentialsDTO $credential, int $role): void;
    public function signin(CredentialsDTO $credentials): AuthDTO;
    public function refresh(Token $token): AuthDTO;
    public function getSignedInUser(Token $token): AuthDTO;
}