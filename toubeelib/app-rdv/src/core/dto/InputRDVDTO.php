<?php

namespace toubeelib\core\dto;

class InputRDVDTO {
    public bool $newPatient;
    public bool $type;
    public bool $isConfirmed;
    public bool $isPaid;
    public \DateTimeImmutable $date;
    public string $praticien_id;
    public string $specialite_id;
    public string $patient_id;
    public string $id;
    
    public function __construct(
        string $praticien_id,
        string $patient_id,
        string $specialite_id,
        \DateTimeImmutable $date,
        bool $newPatient,
        bool $type,
        bool $isConfirmed,
        bool $isPaid,
        
    ) {
        $this->validateDate($date);
        $this->newPatient = $newPatient;
        $this->type = $type;
        $this->isConfirmed = $isConfirmed;
        $this->isPaid = $isPaid;
        $this->date = $date;
        $this->praticien_id = $praticien_id;
        $this->specialite_id = $specialite_id;
        $this->patient_id = $patient_id;
    }

    private function validateDate(\DateTimeImmutable $date): void
    {
        $now = new \DateTimeImmutable();
        if ($date <= $now) {
            throw new \Exception('La date du rendez-vous doit être supérieure à la date actuelle.');
        }
    } 
}