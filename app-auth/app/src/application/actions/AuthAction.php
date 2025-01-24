<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use app\providers\auth\JwtAuthProvider;
use toubeelib\core\dto\CredentialsDTO;
use toubeelib\core\services\auth\AuthenticationException;
use Slim\Psr7\Response as SlimResponse;

class AuthAction extends AbstractAction {
    private JwtAuthProvider $JwtAuthProvider;

    public function __construct(JwtAuthProvider $JwtAuthProvider) {
        $this->JwtAuthProvider = $JwtAuthProvider;
    }

    public function __invoke(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();

        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->respondWithError($response, 'Email and password are required', 400);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->respondWithError($response, 'Invalid email format', 400);
        }

        try {
            $authDTO = new CredentialsDTO($data['email'], $data['password']);
            $authToken = $this->JwtAuthProvider->signin($authDTO);

            $responseData = [
                'accessToken' => $authToken->getAccessToken(),
                'refreshToken' => $authToken->getRefreshToken(),
            ];

            $response->getBody()->write(json_encode($responseData));
            $request = $request->withAttribute('jwt', $authToken->getAccessToken());
            $response = $response->withHeader('Authorization', 'Bearer ' . $authToken->getAccessToken());
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (AuthenticationException $e) {
            return $this->respondWithError($response, 'Invalid credentials', 401);
        }
    }

    private function respondWithError(Response $response, string $message, int $status): Response {
        $responseData = ['error' => $message];
        $response->getBody()->write(json_encode($responseData));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}