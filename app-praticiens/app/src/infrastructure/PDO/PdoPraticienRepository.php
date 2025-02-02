<?php
namespace toubeelib\infrastructure\PDO;

use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\praticien\Specialite;
use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\dto\RDVDTO;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use PDOException;
use PDO;

class PdoPraticienRepository implements PraticienRepositoryInterface
{
    private PDO $pdo;
    private PDO $rdvPdo;

    public function __construct(PDO $pdo, PDO $rdvPdo)
    {
        $this->pdo = $pdo;
        $this->rdvPdo = $rdvPdo;
    }  

    public function getPraticienById(string $id): Praticien
    {
        try {
            $stmt = $this->pdo->prepare('
            SELECT p.*, s.id AS specialite_id, s.label AS specialite_label, s.description AS specialite_description
            FROM praticien p
            JOIN specialite s ON p.specialite_id = s.id
            WHERE p.id = :id
        ');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                throw new PDOException();
            }

            $specialite = new Specialite($row['specialite_id'], $row['specialite_label'], $row['specialite_description']);
            $praticien = new Praticien($row['nom'], $row['prenom'], $row['adresse'], $row['telephone']);
            $praticien->setSpecialite($specialite);
            $praticien->setID($row['id']);

            return $praticien;
        } catch (PDOException $e) {
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }
    }

    public function getAllPraticiens(): array
    {
        try{
            $stmt = $this->pdo->prepare('
                SELECT p.*, s.id AS specialite_id, s.label AS specialite_label, s.description AS specialite_description
                FROM praticien p
                JOIN specialite s ON p.specialite_id = s.id
            ');

            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $praticiens = [];
            foreach ($rows as $row) {
                $specialite = new Specialite($row['specialite_id'], $row['specialite_label'], $row['specialite_description']);
                $praticien = new Praticien($row['nom'], $row['prenom'], $row['adresse'], $row['telephone']);
                $praticien->setSpecialite($specialite);
                $praticien->setID($row['id']);
                $praticiens[] = $praticien;
            }

            return $praticiens;

        }catch(PDOException $e){
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }
    }

    public function getRendezVousPraticien(string $praticien_id, \DateTimeInterface $dateDebut, \DateTimeInterface $dateFin): array {
        try {
            $stmt = $this->rdvPdo->prepare('SELECT * FROM rdv WHERE praticien_id = :praticien_id AND date_heure BETWEEN :dateDebut AND :dateFin');
            $stmt->execute([
                'praticien_id' => $praticien_id,
                'dateDebut' => $dateDebut->format('Y-m-d H:i:s'),
                'dateFin' => $dateFin->format('Y-m-d H:i:s')
            ]);
            $rdvs = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $rdvs[] = new RDVDTO(new RendezVous($row['id'], $row['praticien_id'], $row['patient_id'], 'fake_spiciality_id', new \DateTimeImmutable($row['date_heure'])));
            }
            return $rdvs;
        } catch (PDOException $e) {
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }
    }

    public function getAllSpecialites(): array{
        try{
            $stmt = $this->pdo->prepare('SELECT * FROM specialite');
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(!$rows){
                throw new PDOException();
            }

            $specialites = [];
            foreach($rows as $row){
                $specialite = new Specialite($row['id'], $row['label'], $row['description']);
                $specialites[] = $specialite;
            }
            return $specialites;
        }catch(PDOException $e){
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }
    }

    public function getSpecialiteById(string $id): Specialite {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM specialite WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                throw new PDOException();
            }

            return new Specialite($row['id'], $row['label'], $row['description']);
        } catch (PDOException $e) {
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }
    }

    public function getPraticienSpecialite(string $id){
        try {
            //retourne l'id de spécialité d'un praticien à partir de l'id du praticien
            $stmt = $this->pdo->prepare('SELECT specialite_id FROM praticien WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$row) {
                throw new PDOException();
            }

            return $row['specialite_id'];
        } catch (PDOException $e) {
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }
    }

    public function getPraticiensBySpecialite(string $id){
        try{
            $stmt = $this->pdo->prepare('
                SELECT * from praticien WHERE specialite_id = :id
            ');

            $stmt->execute(['id' => $id]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $praticiens = [];
            foreach ($rows as $row) {
                //on creer un objet praticien à partir des données récupérées
                $praticien = new Praticien($row['nom'], $row['prenom'], $row['adresse'], $row['telephone']);
                $praticien->setID($row['id']);
                $praticiens[] = $praticien;
            }

            return $praticiens;
        }catch(PDOException $e){
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }

    }

    public function save(Praticien $praticien): string {
        try {
            $stmt = $this->pdo->prepare('
                INSERT INTO praticien (nom, prenom, adresse, telephone, specialite_id)
                VALUES (:nom, :prenom, :adresse, :telephone, :specialite_id)
            ');

            $stmt->execute([
                'nom' => $praticien->nom,
                'prenom' => $praticien->prenom,
                'adresse' => $praticien->adresse,
                'telephone' => $praticien->telephone,
                'specialite_id' => $praticien->getSpecialite()->ID
            ]);

            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }
        
    }

}
