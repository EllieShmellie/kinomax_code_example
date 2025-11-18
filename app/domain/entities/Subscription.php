<?php

declare(strict_types=1);

namespace app\domain\entities;

final class Subscription
{
    public function __construct(
        private readonly Subscriber $subscriber
    ) {
    }

    public function subscriber(): Subscriber
    {
        return $this->subscriber;
    }
}
