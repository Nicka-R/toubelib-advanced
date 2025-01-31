<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\application\renderer\JsonRenderer;

class SpecialiteByIdAction extends AbstractAction {
    private ServicePraticienInterface $servicePraticien;

    public function __construct(ServicePraticienInterface $servicePraticien) {
        $this->servicePraticien = $servicePraticien;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $id = $args['id'];
        $specialite = $this->servicePraticien->getSpecialiteById($id);
        $praticiens = $this->servicePraticien->getPraticiensBySpecialite($id);
        //récupérer l'id des praticiens
        $praticienLinks = [];
        foreach ($praticiens as $praticien) {
            $praticienLinks[] = [
                'href' => "/praticiens/{$praticien->getID()}",
                'id' => $praticien->getID(),
            ];
        }

        if ($specialite === null) {
            return JsonRenderer::render($response, 404, ['error' => 'Specialite not found']);
        }
        $responseData = [
            'self' => [
                "href" => "/specialites/{$specialite->ID}",
                "id" => $specialite->ID
            ],
            'label' => $specialite->label,
            'description' => $specialite->description,
            'praticiens' => $praticienLinks,
            'specialites' => [
                'href' => '/specialites',
            ],

        ];
        
        return JsonRenderer::render($response, 200, $responseData);
    }
}