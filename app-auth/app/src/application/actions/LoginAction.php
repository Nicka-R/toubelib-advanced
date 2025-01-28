<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as Response;
use toubeelib\core\services\auth\ServiceAuthInterface;
use toubeelib\core\dto\CredentialsDTO;
use toubeelib\core\services\auth\AuthenticationException;

class LoginAction extends AbstractAction {
    private ServiceAuthInterface $authService;

    public function __construct(ServiceAuthInterface $authService) {
        $this->authService = $authService;
    }

    public function __invoke(ServerRequestInterface $rq, Response $rs, array $args): Response {
        try {
            $data = $rq->getParsedBody();

            if (!$data || empty($data['email']) || empty($data['password'])) {
                return $this->respondWithError($rs, 'Il manque un crédential', 400);
            }

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return $this->respondWithError($rs, 'Format de l\'email invalide', 400);
            }

            $credentials = new CredentialsDTO($data['email'], $data['password']);

            $authDto = $this->authService->login($credentials);

            $responseData = [
                'success' => true,
                'data' => [
                    'accessToken' => $authDto->accessToken,
                    'refreshToken' => $authDto->refreshToken,
                ],
            ];        
            
            $rs->getBody()->write(json_encode($responseData));
            // $rq = $rq->withAttribute('jwt', $authDto->accessToken);
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