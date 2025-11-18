<?php

declare(strict_types=1);

namespace app\infrastructure\notification;

use app\domain\entities\Subscriber;
use app\domain\services\NotifierInterface;
use yii\mail\MailerInterface;

final class EmailNotifier implements NotifierInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly string $fromEmail
    ) {
    }

    public function notify(Subscriber $subscriber, array $payload): void
    {
        $this->mailer
            ->compose('premiere-notification', $payload)
            ->setFrom($this->fromEmail)
            ->setTo($subscriber->email())
            ->setSubject($payload['subject'] ?? 'Upcoming premiere')
            ->send();
    }
}
