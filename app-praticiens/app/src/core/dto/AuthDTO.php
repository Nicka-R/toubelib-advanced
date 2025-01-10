<?php

namespace toubeelib\core\dto;

class AuthDTO extends DTO {
    protected string $id;
    protected string $email;
    protected ?string $hashed_password;
    protected int $role;
    protected string $accessToken;
    protected string $refreshToken;

    public function __construct(string $id, string $email, ?string $hashed_password, int $role, string $accessToken = '', string $refreshToken = '') {
        $this->id = $id;
        $this->email = $email;
        $this->hashed_password = $hashed_password;
        $this->role = $role;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getRole(): int {
        return $this->role;
    }

    public function getHashedPassword(): string {
        return $this->hashed_password;
    }

    public function getAccessToken(): string {
        return $this->accessToken;
    }

    public function getRefreshToken(): string {
        return $this->refreshToken;
    }

    public function setAccessToken(string $accessToken): void {
        $this->accessToken = $accessToken;
    }

    public function setRefreshToken(string $refreshToken): void {
        $this->refreshToken = $refreshToken;
    }
}