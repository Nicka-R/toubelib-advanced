<?php

namespace toubeelib\infrastructure\repositories;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\dto\RDVDTO;
use toubeelib\core\repositoryInterfaces\RDVRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class ArrayRdvRepository implements RDVRepositoryInterface
{
    private array $rdvs = [];

    public function __construct() {
            $r1 = new RendezVous('p1', 'pa1', 'A', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2024-09-02 09:00') );
            $r1->setID('r1');
            $r2 = new RendezVous('p1', 'pa1', 'A', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2024-09-02 10:00'));
            $r2->setID('r2');
            $r3 = new RendezVous('p2', 'pa1', 'A', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2024-09-02 09:30'));
            $r3->setID('r3');

        $this->rdvs  = ['r1'=> $r1, 'r2'=>$r2, 'r3'=> $r3 ];
    }

    public function getRDVs(): array {
        return array_map(fn(RendezVous $rdv) => $rdv->toDTO(), $this->rdvs);
    }

    public function getRendezVousById(string $id): RendezVous
    {
        if (!isset($this->rdvs[$id])) {
            throw new RepositoryEntityNotFoundException("Rendez vous introuvable");
        }
        return $this->rdvs[$id];
    }

    public function getRendezVousByPraticienAndDate(string $praticien_id, \DateTimeImmutable $date): array
    {
        $rdvs = [];
        foreach ($this->rdvs as $rdv) {
            if ($rdv->getPraticienID() === $praticien_id && $rdv->getDate() == $date) {
                $rdvs[] = $rdv;
            }
        }
        return $rdvs;
    }

    public function getRendezVousPraticien(string $praticien_id, \DateTimeImmutable $dateDebut, \DateTimeImmutable $dateFin): array
    {
        $rdvs = [];
        foreach ($this->rdvs as $rdv) {
            if ($rdv->getPraticienID() === $praticien_id && $rdv->getDate() >= $dateDebut && $rdv->getDate() <= $dateFin) {
                $rdvs[] = $rdv;
            }
        }
        return $rdvs;
    }

    public function save(RendezVous $rdv): string{
        $id = Uuid::uuid4()->toString();
        $rdv->setID($id);
        $this->rdvs[$id] = $rdv;
        return $id;
    }

    public function update(RendezVous $rdv): string {        
        if (isset($this->rdvs[$rdv->getID()])) {            
            $this->rdvs[$rdv->getID()] = $rdv;
            return $rdv->getId();
        }
    }
  
}