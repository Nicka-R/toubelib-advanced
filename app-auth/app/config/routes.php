<?php

use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubeelib\application\actions\HomeAction;

return function(App $app): App {

    // Public routes
    $app->get('/', HomeAction::class)->setName('home');

    // Authorization
    // $app->post('/auth/signin', AuthAction::class)->setName('authSignin');
                                                            
    $app->options('/{routes:.+}', function (Request $request, Response $response) {
        return $response;
    });

    return $app;
};