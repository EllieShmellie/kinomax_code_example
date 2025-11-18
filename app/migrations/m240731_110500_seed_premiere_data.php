<?php

declare(strict_types=1);

use yii\db\Migration;

final class m240731_110500_seed_premiere_data extends Migration
{
    public function safeUp(): void
    {
        $now = time();
        $this->batchInsert('{{%subscribers}}', ['email', 'timezone', 'is_paused', 'created_at', 'updated_at'], [
            ['sofia.ivanova@kinomail.ru', 'Europe/Moscow', 0, $now, $now],
            ['max.petrov@kinomail.ru', 'Europe/Moscow', 0, $now, $now],
            ['liza.guseva@cineclub.io', 'Europe/London', 0, $now, $now],
            ['artem.baranov@cineclub.io', 'America/New_York', 0, $now, $now],
            ['viewer.pause@cineclub.io', 'UTC', 1, $now, $now],
        ]);

        $this->batchInsert('{{%releases}}', ['title', 'premiere_at', 'is_notified', 'created_at', 'updated_at'], [
            ['«Ледяной купол» — спецпоказ', gmdate('Y-m-d H:i:s', strtotime('+2 days')), 0, $now, $now],
            ['Ночь короткого метра', gmdate('Y-m-d H:i:s', strtotime('+3 days 4 hours')), 0, $now, $now],
            ['Ретроспектива Калатозова', gmdate('Y-m-d H:i:s', strtotime('+5 days')), 0, $now, $now],
            ['Альманах «Город будущего»', gmdate('Y-m-d H:i:s', strtotime('+8 days')), 0, $now, $now],
        ]);

        $subscriberIds = $this->db->createCommand('SELECT id FROM {{%subscribers}} ORDER BY id')->queryColumn();
        $releaseIds = $this->db->createCommand('SELECT id FROM {{%releases}} ORDER BY id')->queryColumn();

        $rows = [];
        foreach ($subscriberIds as $subscriberId) {
            foreach (array_slice($releaseIds, 0, 3) as $releaseId) {
                $rows[] = [$subscriberId, $releaseId];
            }
        }

        $this->batchInsert('{{%release_subscriptions}}', ['subscriber_id', 'release_id'], $rows);
    }

    public function safeDown(): void
    {
        $this->delete('{{%release_subscriptions}}');
        $this->delete('{{%releases}}');
        $this->delete('{{%subscribers}}');
    }
}
