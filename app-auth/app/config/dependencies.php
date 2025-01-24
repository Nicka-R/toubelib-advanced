<?php
use toubeelib\core\repositoryInterfaces\UserRepositoryInterface;
use toubeelib\core\services\auth\AuthService;
use toubeelib\core\services\auth\AuthServiceInterface;
use app\providers\auth\JwtAuthProvider;
use toubeelib\infrastructure\PDO\PdoUserRepository;
use app\middlewares\auth\CheckJwtToken;
use Psr\Container\ContainerInterface;

return [
    
    'auth.pdo' => function (ContainerInterface $container) {
        $config = parse_ini_file(__DIR__ . '/auth.db.ini');
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },

    UserRepositoryInterface::class => function (ContainerInterface $container) {
        $pdo = $container->get('auth.pdo');
        return new PdoUserRepository($pdo);
    },

    AuthServiceInterface::class => function (ContainerInterface $container) {
        $userRepository = $container->get(UserRepositoryInterface::class);
        return new AuthService($userRepository);
    },

    AuthService::class => function (ContainerInterface $container) {
        $userRepository = $container->get(UserRepositoryInterface::class);
        return new AuthService($userRepository);
    },

    JwtAuthProvider::class => function (ContainerInterface $container) {
        $authService = $container->get(AuthService::class);
        return new JwtAuthProvider($authService, $container->get(UserRepositoryInterface::class));
    },

    CheckJwtToken::class => function (ContainerInterface $container) {
        $config = include __DIR__ . '/config.php';
        $jwtSecret = $config['jwt']['secret'];
        return new CheckJwtToken($jwtSecret);
    },

];

   