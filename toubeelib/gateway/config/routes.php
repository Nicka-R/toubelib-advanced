<?php

use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubeelib\application\actions\HomeAction;
use toubeelib\application\actions\GenericPraticienAction;
use app\middlewares\cors\Cors;

return function(App $app): App {
    $app->add(Cors::class);

    // Public routes
    $app->get('/', HomeAction::class)->setName('home');

    // Praticiens
    $app->get('/praticiens[/{id}[/{rdvs}]]', GenericPraticienAction::class)->setName('praticiens');

                                                            
    $app->options('/{routes:.+}', function (Request $request, Response $response) {
        return $response;
    });

    return $app;
};