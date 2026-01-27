<?php

use yii\db\Migration;

final class m260127_071605_create_author_subscription extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): void
    {
        $this->createTable('{{%author_subscription}}', [
            'id'         => $this->primaryKey(),
            'phone'      => $this->string()->notNull(),
            'author_id'  => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_author_subscription_author_id',
            '{{%author_subscription}}',
            'author_id',
            '{{%author}}',
            'id'
        );

        $this->createIndex(
            'unique_author_subscription_phone_author_id',
            '{{%author_subscription}}',
            ['phone', 'author_id'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%author_subscription}}');
    }
}
