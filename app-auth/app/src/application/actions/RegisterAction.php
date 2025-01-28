<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\core\services\auth\ServiceAuthInterface;
use toubeelib\core\services\auth\AuthenticationException;
use toubeelib\core\dto\CredentialsDTO;

class RegisterAction extends AbstractAction
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

            if (!$data) {
                return $this->respondWithError($rs, 'Body invalide ou vide', 400);
            }

            if (!isset($data['role']) || !is_numeric($data['role'])) {
                return $this->respondWithError($rs, 'Un role valide est requis', 400);
            }

            if (!isset($data['email']) || !isset($data['password'])) {
                return $this->respondWithError($rs, 'Email ou mot de passe requis', 400);
            }

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return $this->respondWithError($rs, 'Email pas correct', 400);
            }

            // Si on veut vérifier la véracité du mot de passe
            // if (strlen($data['password']) < 8) {
            //     return $this->respondWithError($rs, 'Le mot doit etre de plus de 8 caractères', 400);
            // }

            // if (!preg_match('/[A-Z]/', $data['password']) || !preg_match('/[0-9]/', $data['password'])) {
            //     return $this->respondWithError($rs, 'Le mot de passe doit contenir au moins une majuscule et un caratère special', 400);
            // }
            $credentials = new CredentialsDTO($data['email'], $data['password']);
            $this->authService->register($credentials, $data['role']);
            
            $responseData = ['message' => 'UTilisateur créé avec succès'];
            $rs->getBody()->write(json_encode($responseData));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(201);

        } catch (AuthenticationException $e) {
            return $this->respondWithError($rs, $e->getMessage(), 400);
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
