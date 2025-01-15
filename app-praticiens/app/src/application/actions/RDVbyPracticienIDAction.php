<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\core\services\rdv\ServiceRDVInterface;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;

class RDVbyPracticienIDAction extends AbstractAction
{
    private ServiceRDVInterface $serviceRDV;

    public function __construct(ServiceRDVInterface $serviceRDV) {
        $this->serviceRDV = $serviceRDV;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $id = (string) $args['id'];
        $data = json_decode($rq->getBody()->getContents(), true);

        // if date debut et date fin ne sont pas renseignées
        if (empty($data['dateDebut']) && empty($data['nbJours'])) {
            return JsonRenderer::render($rs, 400, ['error' => 'Veuillez renseigner une date de début et un nombre de jours.']);
        }

        try {
            $rdvDTOs = $this->serviceRDV->listerRendezVousPraticien($id, new \DateTimeImmutable($data['dateDebut']), $data['nbJours']);
            $responseData = [];
            foreach ($rdvDTOs as $rdvDTO) {
                $responseData[] = [
                    'self' => "/rdvs/{$rdvDTO->getId()}",
                    'praticienId' => $rdvDTO->getPraticienID(),
                    'patientId' => $rdvDTO->getPatientID(),
                    'creneau' => $rdvDTO->getDate(),
                ];
            }
            return JsonRenderer::render($rs, 200, $responseData);

        } catch (ServiceRDVInvalidDataException $e) {
            return JsonRenderer::render($rs, 404, ['error' => $e->getMessage()]);
        }
    }
}
