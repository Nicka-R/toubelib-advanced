<?php
namespace toubeelib\infrastructure\PDO;

use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\praticien\Specialite;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use PDOException;
use PDO;

class PdoPraticienRepository implements PraticienRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
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

    //ToDO : methode a faire
    public function getSpecialiteById(string $id): Specialite {
        return new Specialite($id, 'label', 'description');
    }

    //ToDO : methode a faire
    public function save(Praticien $praticien): string {
        return '1';
    }

}
