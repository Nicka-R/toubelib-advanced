<?php

namespace App\EmailService;

interface EmailSenderInterface
{
    public function send(string $to, string $subject, string $body): void;
} 