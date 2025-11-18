<?php

declare(strict_types=1);

namespace app\infrastructure\persistence\repository;

use app\domain\repositories\SubscriptionWriterInterface;
use app\infrastructure\persistence\record\PremiereRecord;
use app\infrastructure\persistence\record\SubscriberRecord;
use app\infrastructure\persistence\record\SubscriptionRecord;
use RuntimeException;

final class SubscriptionWriter implements SubscriptionWriterInterface
{
    public function subscribe(string $email, string $timezone, int $premiereId): void
    {
        $premiere = PremiereRecord::findOne($premiereId);
        if ($premiere === null) {
            throw new RuntimeException('Premiere not found');
        }

        $subscriber = SubscriberRecord::findOne(['email' => $email]) ?? new SubscriberRecord();
        $subscriber->email = $email;
        $subscriber->timezone = $timezone;
        $subscriber->is_paused = false;

        if (!$subscriber->save()) {
            throw new RuntimeException('Unable to save subscriber');
        }

        $subscription = SubscriptionRecord::findOne([
            'subscriber_id' => $subscriber->id,
            'release_id' => $premiere->id,
        ]) ?? new SubscriptionRecord();

        $subscription->subscriber_id = $subscriber->id;
        $subscription->release_id = $premiere->id;

        if (!$subscription->save()) {
            throw new RuntimeException('Unable to save subscription');
        }
    }
}
