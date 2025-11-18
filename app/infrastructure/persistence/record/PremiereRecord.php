<?php

declare(strict_types=1);

namespace app\infrastructure\persistence\record;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

final class PremiereRecord extends ActiveRecord
{
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public static function tableName(): string
    {
        return '{{%releases}}';
    }

    public function rules(): array
    {
        return [
            [['title', 'premiere_at'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['premiere_at'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['is_notified'], 'boolean'],
        ];
    }

    public function getSubscriptions(): ActiveQuery
    {
        return $this->hasMany(SubscriptionRecord::class, ['release_id' => 'id']);
    }
}
