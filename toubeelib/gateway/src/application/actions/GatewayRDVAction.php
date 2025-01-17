<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\ClientInterface;
use Slim\Exception\HttpNotFoundException;

class GatewayRDVAction extends AbstractAction
{
    private ClientInterface $remote_rdv_service;

    public function __construct(ClientInterface $rdv_client) {
        $this->remote_rdv_service = $rdv_client;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $method = $rq->getMethod();
        $path = $rq->getUri()->getPath();
        
        try {
            $options = [];
            if ($method === 'POST' || $method === 'PATCH') {
                $options['json'] = json_decode($rq->getBody()->getContents(), true);
            }

            $response = $this->remote_rdv_service->request($method, $path, $options);
            
            $rs = $rs->withHeader('Content-Type', 'application/json');
            $rs->getBody()->write($response->getBody()->getContents());
            return $rs->withStatus($response->getStatusCode());
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            if ($statusCode === 400) {
                $errorBody = $e->getResponse()->getBody()->getContents();
                $errorData = json_decode($errorBody, true);
                $errorMessage = $errorData['error'] ?? 'Bad Request';
                $rs->getBody()->write(json_encode(['error' => $errorMessage]));
                return $rs->withStatus(400)->withHeader('Content-Type', 'application/json');
            } elseif ($statusCode === 404) {
                throw new HttpNotFoundException($rq, 'Resource not found');
            } else {
                throw $e;
            }
        }
    }
}