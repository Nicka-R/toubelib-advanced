<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\core\services\rdv\ServiceRDVInterface;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;
use toubeelib\core\services\rdv\ServiceRDVNotFoundException;
use Psr\Log\LoggerInterface;
use toubeelib\core\dto\InputRDVDTO;

class CreerRDVAction extends AbstractAction
{
    private ServiceRDVInterface $serviceRDV;
    
    public function __construct(ServiceRDVInterface $serviceRDV) {
        $this->serviceRDV = $serviceRDV;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
            $data = json_decode($rq->getBody()->getContents(), true);

            $inputRDV = new InputRDVDTO(
                $data['practicien_id'],
                $data['patient_id'],
                $data['specialite_id'],
                new \DateTimeImmutable($data['date']),
                $data['newPatient'],
                $data['type'],
                $data['isConfirmed'],
                $data['isPaid']
            );

            $rdv = $this->serviceRDV->creerRendezVous($inputRDV);
            $responseData = [
                'rdvId' => $rdv->getId(),
            ];

            return JsonRenderer::render($rs, 200, $responseData);


    }
}
