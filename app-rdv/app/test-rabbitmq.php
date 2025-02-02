<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Connexion à RabbitMQ
$connection = new AMQPStreamConnection(
    'rabbitmq',  // hostname
    5672,        // port
    'admin',     // user
    'JFeja57'    // password
);

$channel = $connection->channel();

// Déclarer l'exchange
$channel->exchange_declare(
    'rdv.events',    // nom de l'exchange
    'topic',         // type
    false,          // passive
    true,           // durable
    false           // auto-delete
);

// Message de test
$data = [
    'event' => 'rdv.event.created',
    'rdv' => [
        'id' => 1,
        'patient' => 'test@example.com',
        'praticien' => 'doc@example.com',
        'date' => '2024-03-20 14:30:00'
    ]
];

$msg = new AMQPMessage(
    json_encode($data),
    ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
);

// Publier le message
$channel->basic_publish(
    $msg,
    'rdv.events',
    'rdv.event.created'
);

echo "Message envoyé!\n";

// Fermer la connexion
$channel->close();
$connection->close(); 