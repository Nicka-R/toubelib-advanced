<?php

namespace toubeelib\core\services\authorization;

use toubeelib\core\domain\entities\user\User;
use toubeelib\core\repositoryInterfaces\RDVRepositoryInterface;

class AuthrzRdvService implements AuthrzRdvServiceInterface
{

    private RDVRepositoryInterface $rdvRepository;

    public function __construct(RDVRepositoryInterface $rdvRepository)
    {
        $this->rdvRepository = $rdvRepository;
    }

    public function isGrantedCreation(string $userId, int $role, string $patientId, string $praticienId): bool
    {
        if ($role !== User::ROLE_PRACTICIEN && $role !== User::ROLE_ADMIN && $role !== User::PATIENT) {
            throw new AuthrzInvalidRoleException("Vous n'avez pas les droits pour effectuer cette action.");
        }

        if ($userId !== $patientId && $userId !== $praticienId) {
            throw new AuthrzNotOwnerException("Vous n'êtes pas le patient ou le praticien du RDV.");
        }

        return true;
    }


    public function isGrantedAccess(string $userId, int $role, string $ressourceId): bool
    {
        $rdv = $this->rdvRepository->getRendezVousById($ressourceId);

        if ($role !== User::ROLE_PRACTICIEN && $role !== User::ROLE_ADMIN && $role !== User::PATIENT) {
            throw new AuthrzInvalidRoleException("Vous n'avez pas les droits pour effectuer cette action.");
        }

        if ($userId !== $rdv->getPatientID() && $userId !== $rdv->getPraticienID()) {
            throw new AuthrzNotOwnerException("Vous n'êtes pas le patient ou le praticien du RDV.");
        }

        return true;
    }


    public function isGrantedAgenda(string $userId, int $role, string $praticienId): bool
    {
        if ($role !== User::ROLE_PRACTICIEN && $role !== User::ROLE_ADMIN) {
            throw new AuthrzInvalidRoleException("Vous n'avez pas les droits pour effectuer cette action.");
        }

        if ($userId !== $praticienId) {
            throw new AuthrzNotOwnerException("Vous n'êtes pas le praticien de ces RDV.");
        }

        return true;
    }
}
