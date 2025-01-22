<?php
namespace toubeelib\core\services\praticien;

interface ServicePraticienInterface {
    public function getPraticienById(string $id): array;
    public function getSpecialiteById(string $id): string;
    public function getPraticienRdvs(string $id): string;
    public function getSpecialitesByPraticienId(string $id): array;

}