<?php

use yii\db\Migration;

final class m260126_123211_create_book_author extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): void
    {
        $this->createTable('{{%book_author}}', [
            'book_id'    => $this->integer()->notNull(),
            'author_id'  => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey('fk_book_author_book_id', '{{%book_author}}', 'book_id', '{{%book}}', 'id');
        $this->addForeignKey('fk_book_author_author_id', '{{%book_author}}', 'author_id', '{{%author}}', 'id');
        $this->createIndex('unique_book_author_book_id_author_id', '{{%book_author}}', ['book_id', 'author_id'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%book_author}}');
    }
}
