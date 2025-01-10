<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\core\services\rdv\ServiceRDVInterface;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;
use toubeelib\core\services\rdv\ServiceRDVNotFoundException;
use Psr\Log\LoggerInterface;

class ModifierRDVAction extends AbstractAction
{
    private ServiceRDVInterface $serviceRDV;
    
    public function __construct(ServiceRDVInterface $serviceRDV) {
        $this->serviceRDV = $serviceRDV;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        try {
            $data = json_decode($rq->getBody()->getContents(), true);
            $id = (string) $args['id'];
            $modified = false;

            if (!empty($data['patient'])) {
                $rdv = $this->serviceRDV->modifierPatient($id, $data['patient']);
                $modified = true;
            }
            
            if (!empty($data['specialite'])) {
                $rdv = $this->serviceRDV->modifierSpecialite($id, $data['specialite']);
                $modified = true;
            }

            if (!$modified) {
                return JsonRenderer::render($rs, 400, ['error' => 'Aucune donnée à modifier.']);
            }

            $responseData = [
                'self' => '/rdv/' . $id,
                'modifier' => '/rdv/' . $id . '/modifier',
                'patientId' => $rdv->getPatientID(),
            ];

            return JsonRenderer::render($rs, 200, $responseData);

        } catch (ServiceRDVNotFoundException $e) {
            // Le rendez-vous n'a pas été trouvé
            return JsonRenderer::render($rs, 404, ['error' => $e->getMessage()]);
        } catch (ServiceRDVInvalidDataException $e) {
            // Les données fournies ne sont pas valides
            return JsonRenderer::render($rs, 400, ['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            // Une erreur interne du serveur
            return JsonRenderer::render($rs, 500, ['error' => 'Une erreur est survenue.']);
        }
    }
}
