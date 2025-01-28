<?php
use toubeelib\core\services\auth\ServiceAuth;
use app\middlewares\auth\CheckJwtToken;
use Psr\Container\ContainerInterface;
use toubeelib\core\services\auth\ServiceAuthInterface;
use toubeelib\core\repositoryInterfaces\AuthRepositoryInterface;
use toubeelib\infrastructure\PDO\PdoAuthRepository;
use toubeelib\core\services\auth\JWTManager;
return [
    
    'auth.pdo' => function (ContainerInterface $container) {
        $config = parse_ini_file(__DIR__ . '/auth.db.ini');
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },

    AuthRepositoryInterface::class => function (ContainerInterface $container) {
        $pdo = $container->get('auth.pdo');
        return new PdoAuthRepository($pdo);
    },

    ServiceAuthInterface::class => function (ContainerInterface $container) {
        $authRepository = $container->get(AuthRepositoryInterface::class);
        $jwtManager = $container->get(JWTManager::class);
        return new ServiceAuth($authRepository, $jwtManager);
    },

    CheckJwtToken::class => function (ContainerInterface $container) {
        $config = include __DIR__ . '/config.php';
        $jwtSecret = $config['jwt']['secret'];
        return new CheckJwtToken($jwtSecret);
    },

];

   