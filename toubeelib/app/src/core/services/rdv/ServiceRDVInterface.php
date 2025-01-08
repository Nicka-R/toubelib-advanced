<?php
namespace toubeelib\core\services\rdv;

use toubeelib\core\dto\RDVDTO;
use toubeelib\core\dto\InputRDVDTO;
use toubeelib\core\dto\SpecialiteDTO;

interface ServiceRDVInterface
{
    /**
     * fonction qui affiche les détails d'un rendez-vous à l'aide de son id
     * @param id l'id du rendez vous 
     * @return le rendez vous voulu
     */
    public function getRDVs() : array ;
    public function getRendezVousById(string $id): RDVDTO;
    public function creerRendezVous(InputRDVDTO $inputRDV): RDVDTO;
    public function getSpecialiteById(string $id): SpecialiteDTO;
    public function getRDVbyPatientID(string $id): array;
    public function isPraticienAvailable(string $praticien_id, \DateTimeImmutable $date): bool;
    public function checkPraticienSpecialites(string $praticienId, string $specialite): bool;
    public function listerDisposPraticien($praticien_id, \DateTimeImmutable $from, \DateTimeImmutable $to);
    public function listerRendezVousPraticien(string $praticien_id, \DateTimeInterface $dateDebut, int $nbJours): array;
    public function modifierRDV(string $rdvID, string $speID = null, string $patientID = null) :  RDVDTO;
    public function modifierPatient(string $id, string $patient): RDVDTO ;
    public function modifierSpecialite(string $id, string $specialite): RDVDTO ;
}