<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\application\renderer\JsonRenderer;

class ListSpecialitesAction extends AbstractAction {
    private ServicePraticienInterface $servicePraticien;

    public function __construct(ServicePraticienInterface $servicePraticien) {
        $this->servicePraticien = $servicePraticien;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $specialites = $this->servicePraticien->getAllSpecialites();

        if ($specialites === null) {
            return JsonRenderer::render($response, 404, ['error' => 'Specialite not found']);
        }

        $responseData = [];
        foreach($specialites as $specialite) {
            $responseData[] = [
                'self' => "/specialites/{$specialite->ID}",
                'id' => $specialite->ID,
                'label' => $specialite->label,
                'description' => $specialite->description
            ];
        }
        return JsonRenderer::render($response, 200, $responseData);
    }
}