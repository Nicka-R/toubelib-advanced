<?php
namespace toubeelib\infrastructure\PDO;


use toubeelib\core\repositoryInterfaces\RDVRepositoryInterface;
use PDOException;
use PDO;
use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\dto\RDVDTO;

class PdoRDVRepository implements RDVRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }  

    public function getRDVs(): array {
        try {
            $stmt = $this->pdo->prepare('
            SELECT r.*, p.id AS praticien_id, p.nom AS praticien_nom, p.prenom AS praticien_prenom, p.adresse AS praticien_adresse, p.telephone AS praticien_telephone
            FROM rdv r
            JOIN praticien p ON r.praticien_id = p.id');
            $stmt->execute();
            $rdvs = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $rdvs[] = new RDVDTO($row);
            }
            return $rdvs;
        } catch (PDOException $e) {
            throw new RepositoryEntityNotFoundException($e->getMessage());
    }
        }

    public function save(RendezVous $rdv): RDVDTO {
        try {
            $formattedDate = $rdv->getDate()->format('Y-m-d H:i:s');
            
            $stmt = $this->pdo->prepare('INSERT INTO rdv (id, date_heure, praticien_id, patient_id) VALUES (:id, :date, :praticien_id, :patient_id)');
            $stmt->execute([
                'id' => $rdv->getID(),
                'date' => $formattedDate,
                'praticien_id' => $rdv->getPraticienID(),
                'patient_id' => $rdv->getPatientID()
            ]);
            
            return new RDVDTO($rdv);
        } catch (PDOException $e) {
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }
    }        
    
    public function getRendezVousByPraticienAndDate(string $praticien_id, \DateTimeImmutable $date): array {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM rdv WHERE praticien_id = :praticien_id AND date_heure = :date');
            $stmt->execute([
                'praticien_id' => $praticien_id,
                'date' => $date->format('Y-m-d')
            ]);
            $rdvs = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $rdvs[] = new RDVDTO($row);
            }
            return $rdvs;
        } catch (PDOException $e) {
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }
    }

    public function getRendezVousById(string $id): RendezVous {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM rdv WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                throw new PDOException();
            }
            $rdv = new RendezVous($row['praticien_id'], $row['patient_id'], "fake_speciality_id", new \DateTimeImmutable($row['date_heure']));
            $rdv->setID($row['id']);
            return $rdv;
        } catch (PDOException $e) {
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }
    }

    public function update(RendezVous $rdv): RDVDTO {
        try {
            $formattedDate = $rdv->getDate()->format('Y-m-d H:i:s');
            
            $stmt = $this->pdo->prepare('UPDATE rdv SET date_heure = :date, praticien_id = :praticien_id, patient_id = :patient_id WHERE id = :id');
            $stmt->execute([
                'id' => $rdv->getID(),
                'date' => $formattedDate,
                'praticien_id' => $rdv->getPraticienID(),
                'patient_id' => $rdv->getPatientID()
            ]);
            
            return new RDVDTO($rdv);
        } catch (PDOException $e) {
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }
    }

    public function getRendezVousByPatientID(string $patient_id): array {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM rdv WHERE patient_id = :patient_id');
            $stmt->execute(['patient_id' => $patient_id]);
            $rdvs = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $rdvs[] = new RDVDTO(new RendezVous($row['praticien_id'], $row['patient_id'], "fake_speciality_id", new \DateTimeImmutable($row['date_heure'])));
            }
            return $rdvs;
        } catch (PDOException $e) {
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }
    }

    public function getRendezVousPraticien(string $praticien_id, \DateTimeInterface $dateDebut, \DateTimeInterface $dateFin): array {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM rdv WHERE praticien_id = :praticien_id AND date_heure BETWEEN :dateDebut AND :dateFin');
            $stmt->execute([
                'praticien_id' => $praticien_id,
                'dateDebut' => $dateDebut->format('Y-m-d H:i:s'),
                'dateFin' => $dateFin->format('Y-m-d H:i:s')
            ]);
            $rdvs = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $rdvs[] = new RDVDTO(new RendezVous($row['praticien_id'], $row['patient_id'], "fake_speciality_id", new \DateTimeImmutable($row['date_heure'])));
            }
            return $rdvs;
        } catch (PDOException $e) {
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }
    }
}