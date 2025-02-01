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
use app\middlewares\authrz\AuthrzRdvMiddleware;

return function(App $app): App {

    // Public routes
    $app->get('/', HomeAction::class)->setName('home');

    // Rendez-vous
    $app->group('/rdvs', function ($group) {
        $group->post('', CreerRDVAction::class)->setName('newRDV');
        $group->get('/{id}', RDVbyIDAction::class)->setName('rdvById');
        $group->patch('/{id}', ModifierRDVAction::class)->setName('modifierRDV'); //Todo
    })->add(AuthrzRdvMiddleware::class); 

    // Routes pour la gestion des rendez-vous par patient et praticien
    $app->group('/patients', function ($group) {
        $group->get('/{id}/rdvs', RDVbyPatientIDAction::class)->setName('rdvByPatientId'); //Todo
    })->add(AuthrzRdvMiddleware::class);

    $app->group('/praticiens', function ($group) {
        $group->get('/{id}/rdvs', RDVbyPracticienIDAction::class)->setName('rdvByPracticienId');
    })->add(AuthrzRdvMiddleware::class); 

                                                            
    $app->options('/{routes:.+}', function (Request $request, Response $response) {
        return $response;
    });

    return $app;
};