<?php
namespace toubeelib\application\actions;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Client;

class ListPraticienAction extends AbstractAction
{
    private Client $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        // $response = $this->client->request('GET', 'http://localhost:6080/praticiens');
        // $praticiens = json_decode($response->getBody()->getContents(), true);
        // return $rs->withJson($praticiens);
    }
}