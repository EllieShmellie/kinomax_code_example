<?php

declare(strict_types=1);

namespace app\application\usecases;

use app\domain\entities\Premiere;
use app\domain\entities\Subscriber;
use app\domain\repositories\PremiereRepositoryInterface;
use app\domain\services\NotifierInterface;
use DateInterval;
use DateTimeImmutable;

final class NotifyUpcomingPremieresHandler
{
    public function __construct(
        private readonly PremiereRepositoryInterface $premiereRepository,
        private readonly NotifierInterface $notifier,
        private readonly int $leadDays = 3
    ) {
    }

    public function handle(DateTimeImmutable $now): int
    {
        $upperBound = $now->add(new DateInterval(sprintf('P%dD', $this->leadDays)));
        $premieres = $this->premiereRepository->findUpcoming($now, $upperBound);
        if ($premieres === []) {
            return 0;
        }

        $notifications = 0;
        foreach ($premieres as $premiere) {
            $notifications += $this->dispatchForPremiere($premiere);
        }

        $this->premiereRepository->markAsNotified(array_map(static fn(Premiere $premiere) => $premiere->id(), $premieres));

        return $notifications;
    }

    private function dispatchForPremiere(Premiere $premiere): int
    {
        $seen = [];
        $count = 0;

        foreach ($premiere->subscriptions() as $subscription) {
            $subscriber = $subscription->subscriber();
            if ($this->shouldSkipSubscriber($subscriber, $seen)) {
                continue;
            }

            $payload = [
                'subject' => sprintf('Скоро премьерный показ: %s', $premiere->title()),
                'releaseTitle' => $premiere->title(),
                'premiereAt' => $premiere->formatPremiereFor($subscriber),
            ];

            $this->notifier->notify($subscriber, $payload);
            $seen[$subscriber->id()] = true;
            $count++;
        }

        return $count;
    }

    /**
     * @param array<int,bool> $seen
     */
    private function shouldSkipSubscriber(Subscriber $subscriber, array $seen): bool
    {
        if ($subscriber->isPaused()) {
            return true;
        }

        return isset($seen[$subscriber->id()]);
    }
}
