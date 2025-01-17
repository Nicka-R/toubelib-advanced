<?php

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use toubeelib\core\services\rdv\ServiceRDV;
use toubeelib\core\services\rdv\ServiceRDVInterface;
use toubeelib\core\repositoryInterfaces\RDVRepositoryInterface;
use toubeelib\infrastructure\PDO\PdoRDVRepository;
use GuzzleHttp\Client;

return [
    'rdv.pdo' => function (ContainerInterface $container) {
        $config = parse_ini_file(__DIR__ . '/rdv.db.ini');
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },

    LoggerInterface::class => function () {
        $logger = new Logger('toubeelib');
        $logfile = __DIR__ . '/../logs/toubeelib.log';
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($logfile, Logger::DEBUG));
        $logger->info('Logger initialisé');
        return $logger;
    },

    'praticien.client' => function () {
        return new Client([
            'base_uri' => 'http://host.docker.internal:6080/',
            'timeout'  => 1000.0,
        ]);
    },

    ServiceRDVInterface::class => function (ContainerInterface $container) {
        $rdvRepository = $container->get(RDVRepositoryInterface::class);
        $praticienClient = $container->get('praticien.client');
        $logger = $container->get(LoggerInterface::class);
        return new ServiceRDV($rdvRepository, $praticienClient, $logger);
    },

    RDVRepositoryInterface::class => function(ContainerInterface $container) {
        $pdo = $container->get('rdv.pdo');
        return new PdoRDVRepository($pdo);
    },
];

   