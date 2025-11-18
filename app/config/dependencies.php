<?php

declare(strict_types=1);

use app\application\usecases\NotifyUpcomingPremieresHandler;
use app\domain\repositories\PremiereRepositoryInterface;
use app\domain\repositories\SubscriptionWriterInterface;
use app\domain\services\NotifierInterface;
use app\infrastructure\notification\EmailNotifier;
use app\infrastructure\persistence\repository\PremiereRepository;
use app\infrastructure\persistence\repository\SubscriptionWriter;
use Yii;

return [
    'container' => [
        'singletons' => [
            PremiereRepositoryInterface::class => PremiereRepository::class,
            SubscriptionWriterInterface::class => SubscriptionWriter::class,
            NotifierInterface::class => static fn() => new EmailNotifier(
                Yii::$app->mailer,
                Yii::$app->params['adminEmail'] ?? 'noreply@example.com'
            ),
            NotifyUpcomingPremieresHandler::class => NotifyUpcomingPremieresHandler::class,
        ],
    ],
];
