<?php

use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\infrastructure\PDO\PdoPraticienRepository;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;

return [

    'auth.pdo' => function (ContainerInterface $container) {
        $config = parse_ini_file(__DIR__ . '/auth.db.ini');
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },

    PraticienRepositoryInterface::class => function (ContainerInterface $container) {
        $pdo = $container->get('praticien.pdo');
        return new PdoPraticienRepository($pdo);
    },
    
    LoggerInterface::class => function () {
        $logger = new Logger('toubeelib');
        $logfile = __DIR__ . '/../logs/toubeelib.log';
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($logfile, Logger::DEBUG));
        $logger->info('Logger initialisé');
        return $logger;
    },

    // Utilisation d'une instance ServicePraticien à chaque utilisation d'une ServicePraticienInterface
    ServicePraticienInterface::class => function (ContainerInterface $container) {
        $praticienRepository = $container->get(PraticienRepositoryInterface::class);
        return new ServicePraticien($praticienRepository);
    },
];