<?php

namespace app\middlewares\authrz;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpForbiddenException;
use Slim\Routing\RouteContext;
use toubeelib\core\services\authorization\AuthrzRdvServiceInterface;
use toubeelib\core\services\authorization\AuthrzInvalidRoleException;
use toubeelib\core\services\authorization\AuthrzNotOwnerException;
use app\providers\JWTManager;

class AuthrzRdvMiddleware
{
    private AuthrzRdvServiceInterface $authrzService;

    public function __construct(AuthrzRdvServiceInterface $authrzService)
    {
        $this->authrzService = $authrzService;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$request->hasHeader('Authorization')) {
            throw new HttpForbiddenException($request, "Missing Authorization header.");
        }
        
        $authHeader = $request->getHeaderLine('Authorization');
        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            throw new HttpForbiddenException($request, "Invalid Authorization header format.");
        }

        $token = $matches[1];

        $jwtManager = new JWTManager();
        $auth_data = $jwtManager->decodeToken($token);
        $route = RouteContext::fromRequest($request)->getRoute();
        $routeName = $route->getName();
        $userId = $auth_data['sub'];
        $userRole = $auth_data['role'];

        try {
            if ($routeName === 'newRDV') {
                $parsedBody = json_decode($request->getBody()->getContents(), true);
    
                if (!isset($parsedBody['patient_id'], $parsedBody['practicien_id'])) {
                    throw new HttpForbiddenException($request, "Les champs patient_id et practicien_id sont requis.");
                }
    
                $this->authrzService->isGrantedCreation($userId, $userRole, $parsedBody['patient_id'], $parsedBody['practicien_id']);
                
            }

            if ($routeName === 'rdvById') {
                $rdvId = $route->getArgument('id');
                $this->authrzService->isGrantedAccess($userId, $userRole, $rdvId);
            }

            if($routeName === 'rdvByPracticienId'){
                $praticienId = $route->getArgument('id');
                $this->authrzService->isGrantedAgenda($userId, $userRole, $praticienId);
            }
        } catch (AuthrzInvalidRoleException | AuthrzNotOwnerException $e) {
            throw new HttpForbiddenException($request, "not authorized : " . $e->getMessage());
        }
        $response = $handler->handle($request);
        return $response;
    }
}
