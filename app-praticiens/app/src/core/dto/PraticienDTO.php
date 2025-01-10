<?php
namespace toubeelib\core\dto;
use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\dto\DTO;

class PraticienDTO extends DTO
{
    protected string $ID;
    protected string $nom;
    protected string $prenom;
    protected string $adresse;
    protected string $tel;
    protected string $specialite_label;

    public function __construct(Praticien $p)
    {
        $this->ID = $p->getID();
        $this->nom = $p->nom;
        $this->prenom = $p->prenom;
        $this->adresse = $p->adresse;
        $this->tel = $p->tel;
        // $this->specialite_label = $p->specialite ? $p->specialite->label : 'Pas de Specialite';
        $this->specialite_label = $p->getSpecialite() ? $p->getSpecialite()->getLabel() : 'Pas de Specialite';
    }

    public function toEntity(): Praticien
    {
        $p = new Praticien($this->nom, $this->prenom, $this->adresse, $this->tel);
        $p->setID($this->ID);
        return $p;
    }

    public function getID(): string
    {
        return $this->ID;
    }
}