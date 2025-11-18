<?php

declare(strict_types=1);

use yii\db\Migration;

final class m240731_100000_create_premiere_notification_tables extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%releases}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'premiere_at' => $this->dateTime()->notNull(),
            'is_notified' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->createIndex('idx_releases_premiere_at', '{{%releases}}', 'premiere_at');
        $this->createIndex('idx_releases_is_notified', '{{%releases}}', 'is_notified');

        $this->createTable('{{%subscribers}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'timezone' => $this->string(64)->defaultValue('UTC'),
            'is_paused' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%release_subscriptions}}', [
            'id' => $this->primaryKey(),
            'subscriber_id' => $this->integer()->notNull(),
            'release_id' => $this->integer()->notNull(),
        ]);
        $this->createIndex('uq_release_subscriber', '{{%release_subscriptions}}', ['subscriber_id', 'release_id'], true);
        $this->addForeignKey(
            'fk_release_subscriptions_subscriber',
            '{{%release_subscriptions}}',
            'subscriber_id',
            '{{%subscribers}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_release_subscriptions_release',
            '{{%release_subscriptions}}',
            'release_id',
            '{{%releases}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown(): void
    {
        $this->dropForeignKey('fk_release_subscriptions_release', '{{%release_subscriptions}}');
        $this->dropForeignKey('fk_release_subscriptions_subscriber', '{{%release_subscriptions}}');
        $this->dropTable('{{%release_subscriptions}}');
        $this->dropTable('{{%subscribers}}');
        $this->dropTable('{{%releases}}');
    }
}
