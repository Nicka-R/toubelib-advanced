<?php

use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubeelib\application\actions\HomeAction;
use toubeelib\application\actions\RDVbyIDAction;
use toubeelib\application\actions\ModifierRDVAction;
use toubeelib\application\actions\CreerRDVAction;
use toubeelib\application\actions\RDVbyPatientIDAction;
use toubeelib\application\actions\RDVbyPracticienIDAction;

return function(App $app): App {

    // Public routes
    $app->get('/', HomeAction::class)->setName('home');

    // Rendez-vous
    $app->post('/rdvs', CreerRDVAction::class)->setName('newRDV');
    $app->get('/rdvs/{id}', RDVbyIDAction::class)->setName('rdvById');
    $app->patch('/rdvs/{id}', ModifierRDVAction::class)->setName('modifierRDV');
    $app->get('/patients/{id}/rdvs', RDVbyPatientIDAction::class)->setName('rdvByPatientId');
    $app->get('/praticiens/{id}/rdvs', RDVbyPracticienIDAction::class)->setName('rdvByPracticienId');

                                                            
    $app->options('/{routes:.+}', function (Request $request, Response $response) {
        return $response;
    });

    return $app;
};