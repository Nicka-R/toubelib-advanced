<?php
namespace app\providers;

use Firebase\JWT\JWT;

class JWTManager {
    private string $secret;

    public function __construct() {
        $config = include(__DIR__ . '/../config/config.php');
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
        $header = ['alg' => 'HS512', 'typ' => 'JWT', 'kid' => 'default-key-id']; 
        return JWT::encode($payload, $this->secret, 'HS512', null, $header);
    }

    /**
     * Crée un Refresh Token à partir du payload
     * @param array $payload
     * @return string
     */
    public function createRefreshToken(array $payload): string {
        $header = ['alg' => 'HS512', 'typ' => 'JWT', 'kid' => 'default-key-id']; 
        return JWT::encode($payload, $this->secret, 'HS512', null, $header);
    }

    /**
     * Décode un token
     * @param string $token
     * @return array
     */
    public function decodeToken(string $token): array {
        $decoded = JWT::decode($token, new \Firebase\JWT\Key($this->secret, 'HS512'));
        
        // Décoder l'en-tête du token pour vérifier `kid`
        $header = json_decode(base64_decode(explode('.', $token)[0]), true);
    
        if (!isset($header['kid']) || $header['kid'] !== 'default-key-id') {
            throw new \Exception('Invalid or missing "kid" in token header');
        }
    
        return (array) $decoded;
    }    
    
}
