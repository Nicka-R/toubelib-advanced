<?php

namespace toubeelib\application\actions;

use toubeelib\core\services\auth\ServiceAuthInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as Response;
use toubeelib\core\services\auth\AuthenticationException;

class RefreshAction extends AbstractAction
{

    private ServiceAuthInterface $authService;

    public function __construct(ServiceAuthInterface $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(ServerRequestInterface $rq, Response $rs, array $args): Response
    {
        try {
            $data = $rq->getParsedBody();

            if (!isset($data['refreshToken']) || empty($data['refreshToken'])) {
                return $this->respondWithError($rs, 'un token est requis', 400);
            }

            $refreshToken = $data['refreshToken'];
            $authDto = $this->authService->refresh($refreshToken);

            $responseData = [
                'success' => true,
                'data' => [
                    'accessToken' => $authDto->accessToken,
                ],
            ];

            $rs->getBody()->write(json_encode($responseData));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (AuthenticationException $e) {
            return $this->respondWithError($rs, $e->getMessage(), 401);
        } catch (\Exception $e) {
            return $this->respondWithError($rs, $e->getMessage(), 500);
        }
    }

    private function respondWithError(Response $response, string $message, int $status): Response
    {
        $responseData = ['error' => $message];
        $response->getBody()->write(json_encode($responseData));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
