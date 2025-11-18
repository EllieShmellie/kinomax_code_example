<?php

declare(strict_types=1);

namespace app\infrastructure\persistence\record;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

final class SubscriptionRecord extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%release_subscriptions}}';
    }

    public function rules(): array
    {
        return [
            [['subscriber_id', 'release_id'], 'required'],
            [['subscriber_id', 'release_id'], 'integer'],
            [['subscriber_id', 'release_id'], 'unique', 'targetAttribute' => ['subscriber_id', 'release_id']],
        ];
    }

    public function getRelease(): ActiveQuery
    {
        return $this->hasOne(PremiereRecord::class, ['id' => 'release_id']);
    }

    public function getSubscriber(): ActiveQuery
    {
        return $this->hasOne(SubscriberRecord::class, ['id' => 'subscriber_id']);
    }
}
