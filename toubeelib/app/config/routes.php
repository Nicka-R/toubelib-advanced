<?php

use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use app\middlewares\auth\CheckJwtToken;
use app\middlewares\auth\AuthrzPraticienMiddleware;
use toubeelib\core\services\auth\AuthrzService;
use toubeelib\application\actions\HomeAction;
use toubeelib\application\actions\RDVbyIDAction;
use toubeelib\application\actions\ModifierRDVAction;
use toubeelib\application\actions\PraticienbyIDAction;
use toubeelib\application\actions\ListPraticiensAction;
use toubeelib\application\actions\CreerRDVAction;
use toubeelib\application\actions\AuthAction;
use toubeelib\application\actions\RDVbyPatientIDAction;
use toubeelib\application\actions\RDVbyPracticienIDAction;
use app\middlewares\cors\Cors;

return function(App $app): App {
    $app->add(Cors::class);

    // Public routes
    $app->get('/', HomeAction::class)->setName('home');

    // Authorization
    // $app->post('/auth/signin', AuthAction::class)->setName('authSignin');

    // Rendez-vous
    $app->post('/rdvs/new', CreerRDVAction::class)->setName('newRDV');
    $app->get('/rdvs/{id}', RDVbyIDAction::class)->setName('rdvById');
    $app->patch('/rdvs/{id}/modifier', ModifierRDVAction::class)->setName('modifierRDV');
    $app->get('/rdvs/patient/{id}', RDVbyPatientIDAction::class)->setName('rdvByPatientId');
    $app->get('/rdvs/practicien/{id}', RDVbyPracticienIDAction::class)->setName('rdvByPracticienId');

    // Practiciens
    $app->get('/praticiens', ListPraticiensAction::class)->setName('praticienById');
    $app->get('/praticiens/{id}', PraticienbyIDAction::class)->setName('praticienById');
    // ->add(CheckJwtToken::class);

                                                            
    $app->options('/{routes:.+}', function (Request $request, Response $response) {
        return $response;
    });

    return $app;
};