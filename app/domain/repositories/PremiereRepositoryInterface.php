<?php

declare(strict_types=1);

namespace app\domain\repositories;

use app\domain\entities\Premiere;
use DateTimeImmutable;

interface PremiereRepositoryInterface
{
    /**
     * @return Premiere[]
     */
    public function findUpcoming(DateTimeImmutable $from, DateTimeImmutable $to): array;

    public function markAsNotified(array $premiereIds): void;

    /**
     * @return Premiere[]
     */
    public function findSchedule(): array;
}
