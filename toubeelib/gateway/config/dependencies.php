<?php

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Slim\App;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use toubeelib\application\actions\GenericAction;

return [

    LoggerInterface::class => function () {
        $logger = new Logger('toubeelib');
        $logfile = __DIR__ . '/../logs/toubeelib.log';
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($logfile, Logger::DEBUG));
        $logger->info('Logger initialisé');
        return $logger;
    },  
    
    'toubeelibClient' => function () {
        return new Client([
            'base_uri' => 'http://api.toubeelib:80/',
            'timeout'  => 1000.0,
        ]);
    },
    
    'praticiensClient' => function () {
        return new Client([
            'base_uri' => 'http://api.praticiens:80/',
            'timeout'  => 1000.0,
        ]);
    },

    'rdvsClient' => function () {
        return new Client([
            'base_uri' => 'http://api.rdv:80/',
            'timeout'  => 1000.0,
        ]);
    },

    GenericAction::class => function(ContainerInterface $container) {
        $praticiensClient = $container->get('praticiensClient');
        $rdvsClient = $container->get('rdvsClient');
        $toubeelibClient = $container->get('toubeelibClient');
        return new GenericAction($praticiensClient, $rdvsClient, $toubeelibClient);
    }

];
