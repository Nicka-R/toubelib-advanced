<?php
namespace app\providers\auth;

use Firebase\JWT\JWT;

class JWTManager {
    private string $secret;

    public function __construct() {
        $config = include(__DIR__ . '/../../config/config.php');
        if (!isset($config['jwt']['secret']) || empty($config['jwt']['secret'])) {
            throw new \InvalidArgumentException('JWT secret key is not set in the configuration file.');
        }
        $this->secret = $config['jwt']['secret'];
    }

    /**
     * Crée un Access Token à partir du payload
     * @param array $payload
     * @return string
     */
    public function createAccessToken(array $payload): string {
        return JWT::encode($payload, $this->secret, 'HS512');
    }

    /**
     * Crée un Refresh Token à partir du payload
     * @param array $payload
     * @return string
     */
    public function createRefreshToken(array $payload): string {
        return JWT::encode($payload, $this->secret, 'HS512');
    }

    /**
     * Décode un token
     * @param string $token
     * @return array
     */
    public function decodeToken(string $token): array {
        return (array) JWT::decode($token, $this->secret, ['HS512']);
    }
}
