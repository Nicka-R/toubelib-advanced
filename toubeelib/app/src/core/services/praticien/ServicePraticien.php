<?php

namespace toubeelib\core\services\praticien;

use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\dto\InputPraticienDTO;
use toubeelib\core\dto\PraticienDTO;
use toubeelib\core\dto\SpecialiteDTO;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class ServicePraticien implements ServicePraticienInterface
{
    private PraticienRepositoryInterface $praticienRepository;  


    public function __construct(PraticienRepositoryInterface $praticienRepository)
    {
        $this->praticienRepository = $praticienRepository;
    }

    /**
     * fonction permettant de créer un praticien avec les données fournies dans le DTO
     * @param InputPraticienDTO $p les données du praticien
     * @throws ServicePraticienInvalidDataException si les données du praticien ne sont pas valides
     */
    public function createPraticien(InputPraticienDTO $p): PraticienDTO
    {
        $praticien = new Praticien($p->nom, $p->prenom, $p->adresse, $p->tel);
        try {
            $specialite = $this->praticienRepository->getSpecialiteById($p->specialite);
            $praticien->setSpecialite($specialite);
            $this->praticienRepository->save($praticien);
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServicePraticienInvalidDataException('Specialite introuvable');
        }
        return new PraticienDTO($praticien);
    }

    /**
     * fonction permettant de récupérer un praticien par son ID
     * @param string $id l'ID du praticien
     * @throws ServicePraticienInvalidDataException si l'ID n'est pas valide
     */
    public function getPraticienById(string $id): PraticienDTO
    {
        try {
            $praticien = $this->praticienRepository->getPraticienById($id);
            return new PraticienDTO($praticien);
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServicePraticienNotFoundException("Praticien introuvable");
        }
    }

    public function getSpecialiteById(string $id): SpecialiteDTO
    {
        try {
            $specialite = $this->praticienRepository->getSpecialiteById($id);
            return $specialite->toDTO();
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServicePraticienInvalidDataException('Invalid Specialite ID');
        }
    }

    /**
     * fonction qui permet de récupérer les spécialités d'un praticien
     * @param string $id l'ID du praticien
     * @throws ServicePraticienInvalidDataException si l'ID n'est pas valide
     * @return array la spécualité du praticien
     */
    public function getSpecialitesByPraticienId(string $id): array
    {
        try {
            $praticien = $this->praticienRepository->getPraticienById($id);
            $specialite = $praticien->getSpecialite();
            return [$specialite->toDTO()];
            
        } catch (RepositoryEntityNotFoundException $e) {
            throw new ServicePraticienInvalidDataException('Invalid Praticien ID');
        }
    }
    
    
    
}