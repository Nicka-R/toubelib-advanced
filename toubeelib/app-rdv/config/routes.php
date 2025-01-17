<?php

use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubeelib\application\actions\CreerRDVAction;
use toubeelib\application\actions\RDVbyIDAction;
use toubeelib\application\actions\ModifierRDVAction;
use toubeelib\application\actions\RDVbyPatientIDAction;

return function(App $app): App {
    $app->post('/rdvs/new', CreerRDVAction::class)->setName('newRDV');
    $app->get('/rdvs/{id}', RDVbyIDAction::class)->setName('rdvById');
    $app->patch('/rdvs/{id}/modifier', ModifierRDVAction::class)->setName('modifierRDV');
    $app->get('/rdvs/patient/{id}', RDVbyPatientIDAction::class)->setName('rdvByPatientId');
                                                            
    $app->options('/{routes:.+}', function (Request $request, Response $response) {
        return $response;
    });

    return $app;
};