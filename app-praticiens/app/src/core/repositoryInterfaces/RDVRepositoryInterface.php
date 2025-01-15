<?php
namespace toubeelib\core\repositoryInterfaces;

use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\dto\RDVDTO;

interface RDVRepositoryInterface
{
    const RDV_ANNULE = -1;
    const RDV_PREVU = 0;
    const RDV_EFFECTUE = 1;
    
    public function save(RendezVous $rdv): RDVDTO;  
    public function getRendezVousByPraticienAndDate(string $praticien_id, \DateTimeImmutable $date): array;  
    public function getRendezVousById(string $id): RendezVous;
    public function update(RendezVous $rdv): RDVDTO;
    public function getRendezVousByPatientID(string $patient_id): array;
    public function getRendezVousPraticien(string $praticien_id, \DateTimeInterface $dateDebut, \DateTimeInterface $dateFin): array;
}
