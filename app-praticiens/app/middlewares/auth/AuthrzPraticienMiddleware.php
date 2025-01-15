<?php
namespace app\middlewares\auth;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpForbiddenException;
use toubeelib\core\services\authorization\AuthrzService;
use toubeelib\core\domain\entities\user\User;

class AuthrzPraticienMiddleware {
    private $authrzService;

    public function __construct(AuthrzService $authrzService) {
        $this->authrzService = $authrzService;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $auth = $request->getAttribute('auth');

        if (!$auth) {
            throw new HttpForbiddenException($request, 'Unauthorized');
        }

        $role = $auth->role;
        $userId = $auth->id;
        $routeContext = $request->getAttribute('routeContext');
        $route = $routeContext->getRoute();
        $routeParams = $route->getArguments();
        $praticienId = $routeParams['id'] ?? null;

        $operation = $this->determineOperation($request->getMethod());
        $resource = 'praticien';

        if (!$this->authrzService->isGranted($userId, $role, $operation, $resource)) {
            throw new HttpForbiddenException($request, 'Forbidden');
        }

        return $handler->handle($request);
    }

    private function determineOperation(string $method): int {
        switch ($method) {
            case 'GET':
                return User::PERMISSION_READ;
            case 'POST':
            case 'PATCH':
            case 'PUT':
                return User::PERMISSION_WRITE;
            case 'DELETE':
                return User::PERMISSION_DELETE;
            default:
                return 0;
        }
    }
}