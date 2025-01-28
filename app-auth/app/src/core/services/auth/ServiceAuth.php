<?php
namespace toubeelib\core\services\auth;

use toubeelib\core\dto\AuthDTO;
use toubeelib\core\dto\CredentialsDTO;
use toubeelib\core\repositoryInterfaces\AuthRepositoryInterface;
use toubeelib\core\services\auth\AuthenticationException;
use toubeelib\infrastructure\PDO\PdoAuthException;
use toubeelib\core\domain\entities\user\User;

class ServiceAuth implements ServiceAuthInterface
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Méthode pour enregistrer un utilisateur
     */
    public function register(CredentialsDTO $credentials, int $role): void {
        try {
            $user = new User($credentials->email, $credentials->password, $role);
            $this->authRepository->register($user, $role);            
        } catch (PdoAuthException $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }

    // public function authenticate(CredentialsDTO $authDTO): AuthDTO
    // {
    //     $user = $this->authRepository->findByEmail($authDTO->email);
    //     // il faut utiliser le password en clair et non le hash
    //     if (!$user || !password_verify($authDTO->password, $user->getHashedPassword())) {
    //         throw new AuthenticationException('Invalid credentials');
    //     }

    //     return new AuthDTO($user->getId(), $user->getEmail(), $user->getHashedPassword(), $user->getRole());
    // }



     


    // public function getSignedInUser(Token $token): AuthDTO {
    //     $decoded = $this->decodeToken($token->getToken());
    //     $user = $this->authRepository->findByEmail($decoded['email']);
    //     if ($user) {
    //         return new AuthDTO(
    //             $user->getId(),
    //             $user->getEmail(),
    //             $user->getHashedPassword(),
    //             $user->getRole(),
    //             $this->createAccessToken(['email' => $user->getEmail()]), 
    //             $token->getToken()
    //         );
    //     }
    //     throw new \Exception("User not found");
    // }
}