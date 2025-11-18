<?php

declare(strict_types=1);

namespace app\domain\services;

use app\domain\entities\Subscriber;

interface NotifierInterface
{
    public function notify(Subscriber $subscriber, array $payload): void;
}
