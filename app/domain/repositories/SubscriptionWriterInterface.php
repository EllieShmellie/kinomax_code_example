<?php

declare(strict_types=1);

namespace app\domain\repositories;

interface SubscriptionWriterInterface
{
    public function subscribe(string $email, string $timezone, int $premiereId): void;
}
