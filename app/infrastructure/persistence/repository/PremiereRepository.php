<?php

declare(strict_types=1);

namespace app\infrastructure\persistence\repository;

use app\domain\entities\Premiere;
use app\domain\entities\Subscriber;
use app\domain\entities\Subscription;
use app\domain\repositories\PremiereRepositoryInterface;
use app\infrastructure\persistence\record\PremiereRecord;
use app\infrastructure\persistence\record\SubscriberRecord;
use app\infrastructure\persistence\record\SubscriptionRecord;
use DateTimeImmutable;

final class PremiereRepository implements PremiereRepositoryInterface
{
    public function findUpcoming(DateTimeImmutable $from, DateTimeImmutable $to): array
    {
        $records = PremiereRecord::find()
            ->alias('p')
            ->with(['subscriptions.subscriber'])
            ->where(['between', 'p.premiere_at', $from->format('Y-m-d H:i:s'), $to->format('Y-m-d H:i:s')])
            ->andWhere(['p.is_notified' => false])
            ->orderBy(['p.premiere_at' => SORT_ASC])
            ->all();

        return array_map([$this, 'mapRecord'], $records);
    }

    public function markAsNotified(array $premiereIds): void
    {
        if ($premiereIds === []) {
            return;
        }

        PremiereRecord::updateAll(['is_notified' => true], ['id' => $premiereIds]);
    }

    public function findSchedule(): array
    {
        $records = PremiereRecord::find()
            ->with(['subscriptions.subscriber'])
            ->orderBy(['premiere_at' => SORT_ASC])
            ->all();

        return array_map([$this, 'mapRecord'], $records);
    }

    private function mapRecord(PremiereRecord $record): Premiere
    {
        $subscriptions = [];
        foreach ($record->subscriptions as $subscriptionRecord) {
            if (!$subscriptionRecord instanceof SubscriptionRecord) {
                continue;
            }

            $subscriberRecord = $subscriptionRecord->subscriber;
            if (!$subscriberRecord instanceof SubscriberRecord) {
                continue;
            }

            $subscriptions[] = new Subscription(
                new Subscriber(
                    (int) $subscriberRecord->id,
                    (string) $subscriberRecord->email,
                    $subscriberRecord->timezone ?: 'UTC',
                    (bool) $subscriberRecord->is_paused
                )
            );
        }

        return new Premiere(
            (int) $record->id,
            (string) $record->title,
            new DateTimeImmutable($record->premiere_at ?? 'now'),
            (bool) $record->is_notified,
            $subscriptions
        );
    }
}
