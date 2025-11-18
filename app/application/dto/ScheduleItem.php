<?php

declare(strict_types=1);

namespace app\application\dto;

use DateTimeImmutable;

final class ScheduleItem
{
    /**
     * @param string[] $subscribers
     */
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly DateTimeImmutable $premiereAt,
        public readonly bool $isNotified,
        public readonly array $subscribers,
        public readonly int $subscriberCount
    ) {
    }
}
