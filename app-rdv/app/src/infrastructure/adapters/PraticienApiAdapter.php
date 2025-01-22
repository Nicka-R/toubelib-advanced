<?php
namespace toubeelib\infrastructure\adapters;

use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\praticien\PraticienDTO;

class PraticienApiAdapter implements ServicePraticienInterface {
    public function getPraticienById(string $id): array {
        $url = "http://api.praticiens:80/praticiens/{$id}";
        $response = file_get_contents($url);
        $praticienData = json_decode($response, true);
        return $praticienData;
    }

    public function getSpecialiteByID(string $id): string {
        $url = "http://api.praticiens:80/specialites/{$id}";
        $response = file_get_contents($url);
        $specialiteData = json_decode($response, true);
        return $specialiteData['nom'];
    }

    public function getPraticienRdvs(string $id): string{
        $url = "http://api.praticiens:80/praticiens/{$id}/rdvs";
        $response = file_get_contents($url);
        $rdvData = json_decode($response, true);
        return $rdvData;
    }

    public function getSpecialitesByPraticienId(string $id): array {
        $url = "http://api.praticiens:80/praticiens/{$id}/specialites";
        $response = file_get_contents($url);
        $specialitesData = json_decode($response, true);
        return $specialitesData;
    }
}