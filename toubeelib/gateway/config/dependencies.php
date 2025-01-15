<?php

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Slim\App;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use toubeelib\application\actions\GatewayPraticienAction;
use toubeelib\application\actions\GenericPraticienAction;

return [

    LoggerInterface::class => function () {
        $logger = new Logger('toubeelib');
        $logfile = __DIR__ . '/../logs/toubeelib.log';
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($logfile, Logger::DEBUG));
        $logger->info('Logger initialisé');
        return $logger;
    },    
    
    'praticiensClient' => function () {
        return new Client([
            'base_uri' => 'http://api.praticiens:80/',
            'timeout'  => 1000.0,
        ]);
    },

    GenericPraticienAction::class => function(ContainerInterface $container) {
        $praticiensClient = $container->get('praticiensClient');
        return new GenericPraticienAction($praticiensClient);
    }

];
