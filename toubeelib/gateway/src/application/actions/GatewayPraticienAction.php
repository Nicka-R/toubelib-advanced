<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\ClientInterface;
use Slim\Exception\HttpNotFoundException;

class GatewayPraticienAction extends AbstractAction
{
    private ClientInterface $remote_praticien_service;

    public function __construct(ClientInterface $client) {
        $this->remote_praticien_service = $client;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $id = $args['id'] ?? null;

        try {
            if ($id) {
                $response = $this->remote_praticien_service->get("praticiens/{$id}");
            } else {
                $response = $this->remote_praticien_service->get('praticiens');
            }
            $rs = $rs->withHeader('Content-Type', 'application/json');
            $rs->getBody()->write($response->getBody()->getContents());
            return $rs->withStatus($response->getStatusCode());
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 404) {
                throw new HttpNotFoundException($rq, 'Praticien not found');
            }
            throw $e;
        }
    }
}