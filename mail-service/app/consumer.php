<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\EmailService\SymfonyEmailSender;

// Connexion à RabbitMQ
$connection = new AMQPStreamConnection(
    'rabbitmq',  // hostname
    5672,        // port
    'admin',     // user
    'JFeja57'    // password
);

$channel = $connection->channel();
$emailSender = new SymfonyEmailSender();

// Déclarer la queue
$channel->queue_declare(
    'mail.notifications',  // nom de la queue
    false,                // passive
    true,                 // durable
    false,               // exclusive
    false                // auto-delete
);

echo " [*] En attente de messages. Pour sortir, pressez CTRL+C\n";

$callback = function ($msg) use ($emailSender) {
    $data = json_decode($msg->body, true);
    
    // Envoyer un email au patient
    $emailSender->send(
        $data['rdv']['patient'],
        'Confirmation de rendez-vous',
        sprintf(
            "Votre rendez-vous est confirmé pour le %s avec le Dr. %s",
            $data['rdv']['date'],
            $data['rdv']['praticien']
        )
    );

    // Envoyer un email au praticien
    $emailSender->send(
        $data['rdv']['praticien'],
        'Nouveau rendez-vous',
        sprintf(
            "Un nouveau rendez-vous est prévu pour le %s avec le patient %s",
            $data['rdv']['date'],
            $data['rdv']['patient']
        )
    );
    
    $msg->ack();  // Acquitter le message
};

$channel->basic_consume(
    'mail.notifications',   // queue
    '',                    // consumer tag
    false,                 // no local
    false,                 // no ack
    false,                 // exclusive
    false,                 // no wait
    $callback              // callback
);

// Boucle pour maintenir le script en attente de messages
while ($channel->is_consuming()) {
    $channel->wait();
}

// Fermer la connexion
$channel->close();
$connection->close(); 