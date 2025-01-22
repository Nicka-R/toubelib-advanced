<?php
namespace toubeelib\core\domain\entities\rdv;

use toubeelib\core\domain\entities\Entity;
use toubeelib\core\domain\entities\praticien\Specialite;
use toubeelib\core\dto\RDVDTO;
use toubeelib\core\dto\PraticienDTO;
class RendezVous extends Entity
{
    private const RDV_ANNULE = -1;
    private const RDV_PREVU = 0;
    private const RDV_HONORE = 1;

    private const RDV_NON_HONORE = 2;
    private const RDV_PAYE = 3;
    private const RDV_TRANSMIS = 4;

    protected string $id;
    protected string $praticienID;
    protected string $patientID;
    protected \DateTimeImmutable $date; 
    protected bool $type = false; // true pour les téléconsultations, false pour le présentiel
    protected bool $newPatient = false; // true si le patient est nouveau, false sinon
    protected int $status= self::RDV_PREVU; // OK si le patient a honoré le rendez-vous, KO si ce n'est pas le cas, EN ATTENTE si le rendez-vous n'a pas encore eu lieu
    protected string $specialiteID;
    protected int $duree = 30; 
    protected Specialite $specialite;

    // protected ?Specialite $specialite = null;

    public function __construct(string $praticien, string $patient, string $specialiteID, \DateTimeImmutable $date)
    {
        $this->praticienID = $praticien;
        $this->patientID = $patient;
        $this->specialiteID = $specialiteID;
        $this->date = $date;
    }

    /*
     * Getters et setters
     */

    public function setSpecialite(Specialite $specialite): void
    {
        $this->specialite = $specialite; 
    }

    public function setPatientID(string $patientID): void
    {
        $this->patientID = $patientID;
    }  

    public function setNewPatient(bool $newPatient): void
    {
        $this->newPatient = $newPatient;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function setType(bool $type): void
    {
        $this->type = $type;
    }

    public function setSpecialiteID(string $specialiteID): void
    {
        $this->specialiteID = $specialiteID;
    }

    public function getId(): ?string
    {
        if (empty($this->id)) {
            return "";
        }
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getType(): bool
    {
        return $this->type;
    }

    public function isNewPatient(): bool
    {
        return $this->newPatient;
    }

    public function getPraticienID(): string
    {
        return $this->praticienID;
    }

    public function getPatientID(): string
    {
        return $this->patientID;
    }

    public function getSpecialiteID(): string
    {
        return $this->specialiteID;
    }

    public function getSpecialite(): ?Specialite
    {
        if (empty($this->specialite)) {
            return null;
        }
        return $this->specialite;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }
    
    public function getStatus(): string
    {
        return $this->status;
    }

    public function annuler(): void
    {
        $this->status = self::RDV_ANNULE;
    }

    public function honorer(): void
    {
        $this->status = self::RDV_HONORE;
    }

    public function nonHonorer(): void
    {
        $this->status = self::RDV_NON_HONORE;
    }

    public function payer(): void
    {
        $this->status = self::RDV_PAYE;
    }

    public function transmettre(): void
    {
        $this->status = self::RDV_TRANSMIS;
    }

    public function isAnnule(): bool
    {
        return $this->status === self::RDV_ANNULE;
    }

    public function toDTO(PraticienDTO $praticienDTO) : RDVDTO
    {
        return new RDVDTO($this, $praticienDTO);	
    }
}