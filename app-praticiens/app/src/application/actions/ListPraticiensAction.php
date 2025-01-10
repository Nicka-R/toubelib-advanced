<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\praticien\ServicePraticienNotFoundException;

class ListPraticiensAction extends AbstractAction{

   private ServicePraticienInterface $servicePraticien;

    public function __construct(ServicePraticienInterface $servicePraticien) {
        $this->servicePraticien = $servicePraticien;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        try {
            $praticiensDto = $this->servicePraticien->getAllPraticiens();
            $responseData = [];
            foreach ($praticiensDto as $praticienDto) {
                $responseData[] = [
                    'self' => "/praticiens/{$praticienDto->ID}",
                    'nom' => $praticienDto->nom,
                    'prenom' => $praticienDto->prenom,
                    'adresse' => $praticienDto->adresse,
                    'tel' => $praticienDto->tel,
                    'specialite' => $praticienDto->specialite_label
                ];
            }
            return JsonRenderer::render($rs, 200, $responseData);

        } catch (ServicePraticienNotFoundException $e) {
            return JsonRenderer::render($rs, 404, ['error' => $e->getMessage()]);
        }

    } 

}