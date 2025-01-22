<?php

namespace toubeelib\core\dto;

use toubeelib\core\dto\DTO;

use toubeelib\core\domain\entities\praticien\Specialite;

class SpecialiteDTO extends DTO
{
    protected string $ID;
    protected string $label;
    protected string $description;

    public function __construct(string $ID, string $label, string $description)
    {
        $this->ID = $ID;
        $this->label = $label;
        $this->description = $description;
    }

    public function toEntity(): Specialite
    {
        $s = new Specialite($this->ID, $this->label, $this->description);
        return $s;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}