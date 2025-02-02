<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use app\providers\JWTManager;
use Exception;

class ValidateTokenAction
{
    private JWTManager $jwtManager;

    public function __construct(JWTManager $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $authHeader = $request->getHeaderLine('Authorization');
            if (empty($authHeader) || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                return $this->respondWithError($response, 'Token is missing or malformed', 401);
            }

            $token = $matches[1];
            $decoded = $this->jwtManager->decodeToken($token);

            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Token is valid',
                'data' => $decoded,
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            return $this->respondWithError($response, $e->getMessage(), 401);
        }
    }

    private function respondWithError(Response $response, string $message, int $status): Response
    {
        $responseData = ['error' => $message];
        $response->getBody()->write(json_encode($responseData));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
