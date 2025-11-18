<?php

declare(strict_types=1);

namespace app\infrastructure\persistence\record;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

final class SubscriberRecord extends ActiveRecord
{
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public static function tableName(): string
    {
        return '{{%subscribers}}';
    }

    public function rules(): array
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
            [['timezone'], 'string', 'max' => 64],
            [['is_paused'], 'boolean'],
        ];
    }

    public function getSubscriptions(): ActiveQuery
    {
        return $this->hasMany(SubscriptionRecord::class, ['subscriber_id' => 'id']);
    }
}
