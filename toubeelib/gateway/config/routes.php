<?php

use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use gateway\application\actions\HomeAction;
use gateway\application\actions\GenericAction;
use app\middlewares\auth\AuthMiddleware;
use app\middlewares\cors\Cors;

return function(App $app): App {
    $app->add(Cors::class);

    // Public routes
    $app->get('/', HomeAction::class)->setName('home');

    $app->group('/rdvs', function($group) {
        $group->map(['GET', 'POST', 'PATCH', 'DELETE'], '[/{params:.*}]', GenericAction::class);
    })->add(AuthMiddleware::class);

    
    
    $app->map(['GET', 'POST', 'PATCH', 'DELETE', 'PUT'], '/{routes:.+}', GenericAction::class)->setName('genericRoute');

                                                            
    $app->options('/{routes:.+}', function (Request $request, Response $response) {
        return $response;
    });

    return $app;
};