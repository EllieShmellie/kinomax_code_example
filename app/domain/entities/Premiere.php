<?php

declare(strict_types=1);

namespace app\domain\entities;

use DateTimeImmutable;
use DateTimeZone;

final class Premiere
{
    /**
     * @param Subscription[] $subscriptions
     */
    public function __construct(
        private readonly int $id,
        private readonly string $title,
        private readonly DateTimeImmutable $premiereAt,
        private readonly bool $notified,
        private readonly array $subscriptions = []
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function premiereAt(): DateTimeImmutable
    {
        return $this->premiereAt;
    }

    public function subscriptions(): array
    {
        return $this->subscriptions;
    }

    public function isNotified(): bool
    {
        return $this->notified;
    }

    public function subscriberCount(): int
    {
        return count($this->subscriptions);
    }

    public function formatPremiereFor(Subscriber $subscriber): string
    {
        $timezone = new DateTimeZone($subscriber->timezone());
        return $this->premiereAt->setTimezone($timezone)->format('d M Y H:i');
    }
}
