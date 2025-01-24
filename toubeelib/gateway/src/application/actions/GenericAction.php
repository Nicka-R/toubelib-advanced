<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\ClientInterface;
use Slim\Exception\HttpNotFoundException;

class GenericAction extends AbstractAction
{
    private ClientInterface $praticienClient;
    private ClientInterface $rdvClient;
    private ClientInterface $toubeelibClient;

    public function __construct(ClientInterface $praticienClient, ClientInterface $rdvClient, ClientInterface $toubeelibClient) {
        $this->praticienClient = $praticienClient;
        $this->rdvClient = $rdvClient;
        $this->toubeelibClient = $toubeelibClient;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $method = $rq->getMethod();
        $path = $rq->getUri()->getPath();
        $body = $rq->getBody()->getContents();

        //on détermine le client à utiliser en fonction du path
        if (strpos($path, '/praticiens') === 0 || strpos($path, '/specialites') === 0) {
            $client = $this->praticienClient;
        } elseif (strpos($path, '/rdvs') === 0) {
            $client = $this->rdvClient;
        } else {
            throw new HttpNotFoundException($rq, 'Route not found');
        }

        try {
            $response = $client->request($method, $path, [
                'body' => $body,
                'headers' => $rq->getHeaders()
            ]);

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