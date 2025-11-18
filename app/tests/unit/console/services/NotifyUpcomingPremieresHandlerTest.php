<?php

declare(strict_types=1);

namespace tests\unit\console\services;

use app\application\usecases\NotifyUpcomingPremieresHandler;
use app\domain\entities\Premiere;
use app\domain\entities\Subscriber;
use app\domain\entities\Subscription;
use app\domain\repositories\PremiereRepositoryInterface;
use app\domain\services\NotifierInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class NotifyUpcomingPremieresHandlerTest extends TestCase
{
    public function testDispatchesOnlyActiveSubscribersAndMarksPremieres(): void
    {
        $premiere = new Premiere(
            42,
            'Аватар 3',
            new DateTimeImmutable('2024-08-01 18:00:00', new \DateTimeZone('UTC')),
            false,
            [
                new Subscription(new Subscriber(7, 'fan@example.com', 'Europe/Moscow', false)),
                new Subscription(new Subscriber(7, 'fan@example.com', 'Europe/Moscow', false)),
                new Subscription(new Subscriber(8, 'silent@example.com', 'Europe/Moscow', true)),
            ]
        );

        $repository = $this->createMock(PremiereRepositoryInterface::class);
        $repository
            ->expects($this->once())
            ->method('findUpcoming')
            ->willReturn([$premiere]);
        $repository
            ->expects($this->once())
            ->method('markAsNotified')
            ->with([42]);

        $notifier = $this->createMock(NotifierInterface::class);
        $notifier
            ->expects($this->once())
            ->method('notify')
            ->with(
                $this->callback(static fn(Subscriber $subscriber) => $subscriber->email() === 'fan@example.com'),
                $this->callback(static fn(array $payload) => $payload['releaseTitle'] === 'Аватар 3')
            );

        $handler = new NotifyUpcomingPremieresHandler($repository, $notifier, 3);
        $count = $handler->handle(new DateTimeImmutable('2024-07-30 10:00:00', new \DateTimeZone('UTC')));

        self::assertSame(1, $count);
    }

    public function testSkipsWhenWindowIsEmpty(): void
    {
        $repository = $this->createMock(PremiereRepositoryInterface::class);
        $repository
            ->expects($this->once())
            ->method('findUpcoming')
            ->willReturn([]);
        $repository
            ->expects($this->never())
            ->method('markAsNotified');

        $notifier = $this->createMock(NotifierInterface::class);
        $notifier
            ->expects($this->never())
            ->method('notify');

        $handler = new NotifyUpcomingPremieresHandler($repository, $notifier, 3);
        $count = $handler->handle(new DateTimeImmutable());

        self::assertSame(0, $count);
    }
}
