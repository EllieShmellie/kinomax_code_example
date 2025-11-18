<?php

declare(strict_types=1);

namespace app\domain\entities;

final class Subscriber
{
    public function __construct(
        private readonly int $id,
        private readonly string $email,
        private readonly string $timezone,
        private readonly bool $paused
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function timezone(): string
    {
        return $this->timezone;
    }

    public function isPaused(): bool
    {
        return $this->paused;
    }
}
