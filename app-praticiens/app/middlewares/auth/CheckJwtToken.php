<?php
namespace app\middlewares\auth;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use toubeelib\core\dto\AuthDTO;
use Slim\Exception\HttpUnauthorizedException;

class CheckJwtToken
{
    private $jwtSecret;

    public function __construct($jwtSecret)
    {
        $this->jwtSecret = $jwtSecret;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface
    {
        $authHeader = $request->getHeader('Authorization')[0] ?? null;

        if (is_null($authHeader) || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            throw new HttpUnauthorizedException($request, 'Missing or invalid JWT token');
        }

        $jwt = $matches[1];

        try {
            $decoded = JWT::decode($jwt, new Key($this->jwtSecret, 'HS512'));
        } catch (\Exception $e) {
            throw new HttpUnauthorizedException($request, 'Invalid JWT token');
        }

        $authDto = new AuthDto($decoded->sub, $decoded->email, null, $decoded->role);
        $request = $request->withAttribute('auth', $authDto);

        $response = $next->handle($request);
        return $response;
    }
}
