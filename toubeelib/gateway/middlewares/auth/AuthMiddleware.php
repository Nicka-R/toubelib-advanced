<?php

namespace app\middlewares\auth;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use GuzzleHttp\ClientInterface;
use Slim\Exception\HttpUnauthorizedException;

class AuthMiddleware implements MiddlewareInterface
{
    private ClientInterface $authClient;

    public function __construct(ClientInterface $authClient) {
        $this->authClient = $authClient;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): Response {
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $response = new Response();
            $response->getBody()->write(json_encode(['error' => 'Token manquant']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $token = $matches[1];

        try {
            $authResponse = $this->authClient->request('POST', '/tokens/validate', [
                'headers' => ['Authorization' => "Bearer $token"]
            ]);

            if ($authResponse->getStatusCode() !== 200) {
                throw new HttpUnauthorizedException($request, 'Token invalide ou expiré');
            }

            return $handler->handle($request);

        } catch (\Exception $e) {
            $response = new Response();
            $response->getBody()->write(json_encode(['error' => 'Token invalide ou expiré']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }
    }
}
