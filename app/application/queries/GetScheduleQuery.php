<?php

declare(strict_types=1);

namespace app\application\queries;

use app\application\dto\ScheduleItem;
use app\domain\entities\Premiere;
use app\domain\repositories\PremiereRepositoryInterface;

final class GetScheduleQuery
{
    public function __construct(
        private readonly PremiereRepositoryInterface $premiereRepository
    ) {
    }

    /**
     * @return ScheduleItem[]
     */
    public function fetch(): array
    {
        $premieres = $this->premiereRepository->findSchedule();

        return array_map(
            static function (Premiere $premiere): ScheduleItem {
                $subscribers = array_map(
                    static fn($subscription) => $subscription->subscriber()->email(),
                    array_slice($premiere->subscriptions(), 0, 3)
                );

                return new ScheduleItem(
                    $premiere->id(),
                    $premiere->title(),
                    $premiere->premiereAt(),
                    $premiere->isNotified(),
                    $subscribers,
                    $premiere->subscriberCount()
                );
            },
            $premieres
        );
    }
}
