<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use app\middlewares\cors\Cors;
use app\middlewares\cors\AddHeaders;



$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/settings.php' );
$builder->addDefinitions(__DIR__ . '/dependencies.php');

$c=$builder->build();
$app = AppFactory::createFromContainer($c);


$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware($c->get('displayErrorDetails'), false, false)
//    ->getDefaultErrorHandler()
//    ->forceContentType('application/json')
;


$app = (require_once __DIR__ . '/routes.php')($app);
$routeParser = $app->getRouteCollector()->getRouteParser();

//ajout du middleware Cors
$app->add(new Cors());
$app->add(new AddHeaders());


return $app;