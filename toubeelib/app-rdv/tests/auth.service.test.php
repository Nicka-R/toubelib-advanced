<?php

// script pour tester le service d'authentification
require_once __DIR__ . '/../vendor/autoload.php';

use toubeelib\core\services\auth\AuthService;
use toubeelib\core\dto\CredentialsDTO;
use toubeelib\core\services\auth\AuthenticationException;
use app\providers\auth\JwtAuthProvider;
use toubeelib\infrastructure\PDO\PdoUserRepository;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;


// Configuration du conteneur de dépendances
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/dependencies.php');

$container = $containerBuilder->build();

try {
    //il faut mettre le host à localhost pour les tests sinon toubeelib.db
    
    // Récupérer l'objet PDO à partir du conteneur de dépendances
    $pdo = $container->get('auth.pdo');
    echo "Connexion à la base de données réussie.\n";
    $userRepository = new PdoUserRepository($pdo);
    $authService = new AuthService(new PdoUserRepository($pdo));
    $JwtAuthProvider = new JwtAuthProvider($authService, $userRepository);

    // Test de la fonction authenticate
    try {
        $user = $authService->authenticate(new CredentialsDTO('jmarin@riviere.com', 'jmarin'));
        print_r($user);
    } catch (AuthenticationException $e) {
        echo $e->getMessage();
    }

    //test avec invalid credentials
    try {
        $user = $authService->authenticate(new CredentialsDTO('sgodard@vasseur.com', 'incorrect'));
        print_r($user);
    } catch (AuthenticationException $e) {
        echo $e->getMessage();
    }

    //test avec JwtAuthProvider
    try{
        $authToken = $JwtAuthProvider->signin(new CredentialsDTO('jmarin@riviere.com', 'jmarin'));
        print_r($authToken);
    } catch (AuthenticationException $e) {
        echo $e->getMessage();
    }

} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage() . "\n";
}