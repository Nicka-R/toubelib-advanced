<?php   
namespace toubeelib\core\services\authorization;
interface AuthrzPraticienInterface{
    public function isGranted(string $id, int $role, int $operation, string $resource): bool;
}