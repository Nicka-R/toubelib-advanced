<?php

namespace App\EmailService;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class SymfonyEmailSender implements EmailSenderInterface
{
    private $mailer;

    public function __construct()
    {
        $transport = Transport::fromDsn('smtp://mailcatcher:1025');
        $this->mailer = new Mailer($transport);
    }

    public function send(string $to, string $subject, string $body): void
    {
        $email = (new Email())
            ->from('noreply@toubeelib.fr')
            ->to($to)
            ->subject($subject)
            ->text($body);

        $this->mailer->send($email);
    }
} 