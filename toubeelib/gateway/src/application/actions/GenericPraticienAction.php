<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\ClientInterface;
use Slim\Exception\HttpNotFoundException;

class GenericPraticienAction extends AbstractAction
{
    private ClientInterface $remote_praticien_service;

    public function __construct(ClientInterface $client) {
        $this->remote_praticien_service = $client;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $id = $args['id'] ?? null;
        $rdv = false;
        //si y a /rdvs à la fin de l'url
        if (strpos($rq->getUri()->getPath(), '/rdvs') !== false) {
            $rdv = true;
        }

        try {
            if ($id) {
                if($rdv) {
                    $query = $rq->getBody()->getContents();
                    $response = $this->remote_praticien_service->get("praticiens/{$id}/rdvs", ['body' => $query]);
                }else{
                    $response = $this->remote_praticien_service->get("praticiens/{$id}");
                }
            } else {
                $response = $this->remote_praticien_service->get('praticiens');
            }
            $rs = $rs->withHeader('Content-Type', 'application/json');
            $rs->getBody()->write($response->getBody()->getContents());
            return $rs->withStatus($response->getStatusCode());
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            //gestion des erreurs
            $statusCode = $e->getResponse()->getStatusCode();
            if ($statusCode === 400) {
                $errorBody = $e->getResponse()->getBody()->getContents();
                $errorData = json_decode($errorBody, true);
                $errorMessage = $errorData['error'] ?? 'Bad Request';
                $rs->getBody()->write(json_encode(['error' => $errorMessage]));
                return $rs->withStatus(400)->withHeader('Content-Type', 'application/json');
            } elseif ($statusCode === 404) {
                throw new HttpNotFoundException($rq, 'Praticien not found');
            } else {
                throw $e;
            }
        }
    }
}