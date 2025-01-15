<?php

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Slim\App;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;

return [

    LoggerInterface::class => function () {
        $logger = new Logger('toubeelib');
        $logfile = __DIR__ . '/../logs/toubeelib.log';
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($logfile, Logger::DEBUG));
        $logger->info('Logger initialisé');
        return $logger;
    },    

    ClientInterface::class => function () {
        return new Client([
            'base_uri' => 'http://host.docker.internal:6080/',
            'timeout'  => 1000.0,
        ]);
    },

];
