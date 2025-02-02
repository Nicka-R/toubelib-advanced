<?php

namespace toubeelib\core\services\auth;

use toubeelib\core\dto\AuthDTO;
use toubeelib\core\dto\CredentialsDTO;
use toubeelib\core\repositoryInterfaces\AuthRepositoryInterface;
use toubeelib\core\services\auth\AuthenticationException;
use toubeelib\infrastructure\PDO\PdoAuthException;
use toubeelib\core\domain\entities\user\User;
use app\providers\JWTManager;


class ServiceAuth implements ServiceAuthInterface
{
    private AuthRepositoryInterface $authRepository;
    private JWTManager $jwtManager;

    public function __construct(AuthRepositoryInterface $authRepository, JWTManager $jwtManager)
    {
        $this->authRepository = $authRepository;
        $this->jwtManager = $jwtManager;
    }

    /**
     * Méthode pour enregistrer un utilisateur
     */
    public function register(CredentialsDTO $credentials, int $role): void
    {
        try {
            $user = new User($credentials->email, $credentials->password, $role);
            $this->authRepository->register($user, $role);
        } catch (PdoAuthException $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }

    /**
     * Méthode pour authentifier un utilisateur
     */
    public function login(CredentialsDTO $credentials): AuthDTO
    {
        try {
            $user = $this->authRepository->login($credentials->email);

            if (!$user || !password_verify($credentials->password, $user->hashed_password)) {
                throw new AuthenticationException('Credentials invalid');
            }

            $accessToken = $this->jwtManager->createAccessToken([
                'sub' => $user->id,
                'role' => $user->role,
                'email' => $user->email,
                'exp' => time() + 3600, // Access token valable 1 heure
            ]);

            $refreshToken = $this->jwtManager->createRefreshToken([
                'sub' => $user->id,
                'role' => $user->role,
                'email' => $user->email,
                'exp' => time() + 86400, // Refresh token valable 24 heures
            ]);

            $authDto = new AuthDTO($user->id, $user->email, $user->hashed_password, $user->role);
            $authDto->setAccessToken($accessToken);
            $authDto->setRefreshToken($refreshToken);
            return $authDto;
        } catch (PdoAuthException $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }

    /**
     * Méthode pour rafraichir un token
     */
    public function refresh(string $refreshToken): AuthDTO
    {
        try {
            $decodedToken = $this->jwtManager->decodeToken($refreshToken);

            $userId = $decodedToken['sub'];
            $authDto = $this->authRepository->getUserById($userId);

            // Générer un nouveau Access Token
            $newAccessToken = $this->jwtManager->createAccessToken([
                'sub' => $authDto->id,
                'role' => $authDto->role,
                'email' => $authDto->email,
                'exp' => time() + 3600,
            ]);

            // //Regénerer également un nouveau refresh token si on veut plus de sécurité
            // $newRefreshToken = $this->jwtManager->createRefreshToken([
            //     'sub' => $authDto->id,
            //     'role' => $authDto->role,
            //     'email' => $authDto->email,
            //     'exp' => time() + 86400,
            // ]);            

            $newAuthDto = new AuthDTO($authDto->id, $authDto->email,$authDto->hashed_password,$authDto->role);
            $newAuthDto->setAccessToken($newAccessToken);
            // $newAuthDto->setRefreshToken($newRefreshToken);
            return $newAuthDto;
        } catch (PdoAuthException $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }
}
