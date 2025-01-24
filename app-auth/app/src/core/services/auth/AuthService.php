<?php
namespace toubeelib\core\services\auth;

use toubeelib\core\dto\AuthDTO;
use toubeelib\core\dto\CredentialsDTO;
use toubeelib\core\repositoryInterfaces\UserRepositoryInterface;
use toubeelib\core\services\auth\AuthenticationException;

class AuthService implements AuthServiceInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function authenticate(CredentialsDTO $authDTO): AuthDTO
    {
        $user = $this->userRepository->findByEmail($authDTO->email);
        // il faut utiliser le password en clair et non le hash
        if (!$user || !password_verify($authDTO->password, $user->getHashedPassword())) {
            throw new AuthenticationException('Invalid credentials');
        }

        return new AuthDTO($user->getId(), $user->getEmail(), $user->getHashedPassword(), $user->getRole());
    }

    public function register(CredentialsDTO $credentials, int $role): void
    {
        $this->userRepository->save($credentials, $role);
    }


    public function getUserById(string $id): AuthDTO
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new AuthenticationException('User not found');
        }

        return new AuthDTO($user->getId(), $user->getEmail(), $user->getHashedPassword(), $user->getRole());
    }
}