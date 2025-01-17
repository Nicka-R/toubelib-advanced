<?php

use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use gateway\middlewares\auth\CheckJwtToken;
use gateway\middlewares\auth\AuthrzPraticienMiddleware;
use toubeelib\application\actions\HomeAction;
use toubeelib\application\actions\GatewayPraticienAction;
use gateway\middlewares\cors\Cors;
use toubeelib\application\actions\GatewayRDVAction;

return function(App $app): App {
    $app->add(Cors::class);

    // Public routes
    $app->get('/', HomeAction::class)->setName('home');

    $app->get('/praticiens[/{id}[/{rdvs}]]', GatewayPraticienAction::class)->setName('praticiens');
                                                            

    $app->post('/rdvs/new', GatewayRDVAction::class)->setName('newRDV');
    $app->get('/rdvs/{id}', GatewayRDVAction::class)->setName('rdvById');
    $app->patch('/rdvs/{id}/modifier', GatewayRDVAction::class)->setName('modifierRDV');
    $app->get('/rdvs/patient/{id}', GatewayRDVAction::class)->setName('rdvByPatientId');
    
    $app->options('/{routes:.+}', function (Request $request, Response $response) {
        return $response;
    });

    return $app;
};  