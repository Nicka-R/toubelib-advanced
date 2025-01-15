<?php

use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use app\middlewares\auth\CheckJwtToken;
use app\middlewares\auth\AuthrzPraticienMiddleware;
use toubeelib\core\services\auth\AuthrzService;
use toubeelib\application\actions\HomeAction;
use toubeelib\application\actions\PraticienbyIDAction;
use toubeelib\application\actions\ListPraticiensAction;
use toubeelib\application\actions\RDVbyPracticienIDAction;

return function(App $app): App {

    // Public routes
    $app->get('/', HomeAction::class)->setName('home');

    // Practiciens
    $app->get('/praticiens', ListPraticiensAction::class)->setName('praticienById');
    $app->get('/praticiens/{id}', PraticienbyIDAction::class)->setName('praticienById');
    
    $app->get('/praticiens/{id}/rdvs', RDVbyPracticienIDAction::class)->setName('rdvByPracticienId');
    // ->add(CheckJwtToken::class);

                                                            
    $app->options('/{routes:.+}', function (Request $request, Response $response) {
        return $response;
    });

    return $app;
};