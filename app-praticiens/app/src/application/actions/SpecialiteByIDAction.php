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

        if ($specialite === null) {
            return JsonRenderer::render($response, 404, ['error' => 'Specialite not found']);
        }
        $responseData = [
            'self' => "/specialites/{$specialite->ID}",
            'label' => $specialite->label,
            'description' => $specialite->description
        ];
        
        return JsonRenderer::render($response, 200, $responseData);
    }
}