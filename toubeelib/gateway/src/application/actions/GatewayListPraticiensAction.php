<?php
namespace toubeelib\application\actions;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\ClientInterface;

class GatewayListPraticiensAction extends AbstractAction
{
    private ClientInterface $remote_praticien_service;

    public function __construct(ClientInterface $client) {
        $this->remote_praticien_service = $client;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $response = $this->remote_praticien_service->get('praticiens');
        return $response;
    }
}