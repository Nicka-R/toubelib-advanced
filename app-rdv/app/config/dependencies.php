<?php

use toubeelib\core\services\rdv\ServiceRDVInterface;
use toubeelib\core\services\rdv\ServiceRDV;
use toubeelib\core\repositoryInterfaces\RDVRepositoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\infrastructure\PDO\PdoRDVRepository;
use toubeelib\infrastructure\adapters\PraticienApiAdapter;
use Slim\App;

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

    // Utilisation d'une instance ServiceRDV à chaque utilisation d'une ServiceRDVInterface
    ServiceRDVInterface::class => function (ContainerInterface $container) {
        $rdvRepository = $container->get(RDVRepositoryInterface::class);
        $servicePracticien = $container->get(ServicePraticienInterface::class);
        
        $logger = $container->get(LoggerInterface::class);
        return new ServiceRDV($rdvRepository, $servicePracticien,$logger);
    }, 

    RDVRepositoryInterface::class => function(ContainerInterface $container) {
        $pdo_rdv = $container->get('rdv.pdo');
        return new PdoRDVRepository($pdo_rdv);
    },

    ServicePraticienInterface::class => function (ContainerInterface $container) {
        return new PraticienApiAdapter();
    },

];

   