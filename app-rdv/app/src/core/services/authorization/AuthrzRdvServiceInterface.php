<?php   
namespace toubeelib\core\services\authorization;
interface AuthrzRdvServiceInterface{
    public function isGrantedCreation(string $userId, int $role, string $patientId, string $praticienId): bool;
    public function isGrantedAccess(string $userId, int $role, string $ressourceId): bool;
    public function isGrantedAgenda(string $userId, int $role, string $praticienId): bool;
}