<?php

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Slim\App;
use GruzzleHttp\Client;

return [

    LoggerInterface::class => function () {
        $logger = new Logger('toubeelib');
        $logfile = __DIR__ . '/../logs/toubeelib.log';
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($logfile, Logger::DEBUG));
        $logger->info('Logger initialisé');
        return $logger;
    },    

    Client::class => function (ContainerInterface $container) {
        return new Client([
            'base_uri' => 'http://localhost:6080',
            'timeout'  => 2.0,
        ]);
    },

];

   