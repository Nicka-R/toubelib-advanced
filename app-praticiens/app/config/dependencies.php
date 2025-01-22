<?php

use toubeelib\core\services\rdv\ServiceRDVInterface;
use toubeelib\core\services\rdv\ServiceRDV;
use toubeelib\core\repositoryInterfaces\RDVRepositoryInterface;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\UserRepositoryInterface;
use toubeelib\infrastructure\PDO\PdoPraticienRepository;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\auth\AuthService;
use toubeelib\core\services\auth\AuthServiceInterface;
use app\providers\auth\JwtAuthProvider;
use toubeelib\infrastructure\PDO\PdoUserRepository;
use toubeelib\infrastructure\PDO\PdoRDVRepository;
use app\middlewares\auth\CheckJwtToken;
use Slim\App;

return [

    'praticien.pdo' => function (ContainerInterface $container) {
        $config = parse_ini_file(__DIR__ . '/praticien.db.ini');
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },

    'rdv.pdo' => function (ContainerInterface $container) {
        $config = parse_ini_file(__DIR__ . '/rdv.db.ini');
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },

    PraticienRepositoryInterface::class => function (ContainerInterface $container) {
        $pdo = $container->get('praticien.pdo');
        $pdoRDV = $container->get('rdv.pdo');
        return new PdoPraticienRepository($pdo, $pdoRDV);
    },
    
    // Utilisation d'une instance ServicePraticien à chaque utilisation d'une ServicePraticienInterface
    ServicePraticienInterface::class => function (ContainerInterface $container) {
        $praticienRepository = $container->get(PraticienRepositoryInterface::class);
        return new ServicePraticien($praticienRepository);
    },

];

   