<?php

declare(strict_types=1);

use yii\symfonymailer\Mailer;

$host = getenv('SMTP_HOST') ?: null;
$port = getenv('SMTP_PORT') ?: null;
$username = getenv('SMTP_USER') ?: null;
$password = getenv('SMTP_PASSWORD') ?: null;
$encryption = getenv('SMTP_ENCRYPTION') ?: null;
$scheme = getenv('SMTP_SCHEME') ?: 'smtp';

$mailer = [
    'class' => Mailer::class,
    'viewPath' => '@app/mail',
    'useFileTransport' => true,
];

if ($host) {
    $mailer['useFileTransport'] = false;
    $mailer['transport'] = array_filter([
        'scheme' => $scheme,
        'host' => $host,
        'username' => $username,
        'password' => $password,
        'port' => $port ? (int) $port : 587,
        'encryption' => $encryption ?: 'tls',
    ]);
}

return $mailer;
