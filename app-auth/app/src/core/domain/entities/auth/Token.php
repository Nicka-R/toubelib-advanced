<?php
namespace toubeelib\core\domain\entities\auth;
class Token {
    public string $value;
    public string $token;

    public function __construct(string $value) {
        $this->value = $value;
    }

    public function getValue(): string {
        return $this->value;
    }

    public function getToken(): string {
        return $this->token;
    }

    public function setToken(string $token): void {
        $this->token = $token;
    }
}