<?php
namespace toubeelib\core\services\authorization;
use toubeelib\core\services\authorization\AuthrzPraticienInterface;

class AuthrzService implements AuthrzPraticienInterface {
    public function isGranted(string $id, int $role, int $operation, string $resource): bool {
        // Logique pour vérifier les autorisations
        if ($role === User::ROLE_ADMIN) {
            return true;
        }

        if ($role === User::ROLE_PRACTITIONER && $resource === 'praticien' && $operation !== User::PERMISSION_DELETE) {
            return $id === $resource;
        }

        return false;
    }
}