<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\core\services\rdv\ServiceRDVInterface;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;

class RDVbyPatientIDAction extends AbstractAction
{
    private ServiceRDVInterface $serviceRDV;

    public function __construct(ServiceRDVInterface $serviceRDV) {
        $this->serviceRDV = $serviceRDV;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $id = (string) $args['id'];

        try {
            $rdvDTOs = $this->serviceRDV->getRDVbyPatientID($id);
            $responseData = [];
            foreach ($rdvDTOs as $rdvDTO) {
                $responseData[] = [
                    'self' => "/rdvs/{$rdvDTO->getId()}",
                    'praticien' => [
                        'href' => "/praticiens/{$rdvDTO->getPraticienID()}",
                        'id' => $rdvDTO->getPraticienID(),
                    ],
                    'patient' => [
                        'id' => $rdvDTO->getPatientID(),
                    ],
                    'creneau' => $rdvDTO->getDate(),
                    'status' => $rdvDTO->getStatus(),
                ];
                
            }
            return JsonRenderer::render($rs, 200, $responseData);

        } catch (ServiceRDVInvalidDataException $e) {
            return JsonRenderer::render($rs, 404, ['error' => $e->getMessage()]);
        }
    }
}
