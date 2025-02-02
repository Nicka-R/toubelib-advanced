<?php
namespace toubeelib\core\services\praticien;
use toubeelib\core\dto\SpecialiteDTO;

interface ServicePraticienInterface {
    public function getPraticienById(string $id): array;
    public function getSpecialiteById(string $id): SpecialiteDTO;
    public function getPraticienRdvs(string $id): string;
    public function getSpecialitesByPraticienId(string $id): SpecialiteDTO;

}