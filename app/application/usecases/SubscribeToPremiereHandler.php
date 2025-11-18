<?php

declare(strict_types=1);

namespace app\application\usecases;

use app\domain\repositories\SubscriptionWriterInterface;

final class SubscribeToPremiereHandler
{
    public function __construct(
        private readonly SubscriptionWriterInterface $writer
    ) {
    }

    public function handle(string $email, string $timezone, int $premiereId): void
    {
        $this->writer->subscribe($email, $timezone, $premiereId);
    }
}
