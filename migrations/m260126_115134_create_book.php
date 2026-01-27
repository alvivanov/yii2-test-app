<?php

use yii\db\Migration;

final class m260126_115134_create_book extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): void
    {
        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'publication_year' => $this->integer(4)->notNull(),
            'isbn' => $this->string(13)->notNull(),
            'main_page_image' => $this->string(500)->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%book}}');
    }
}
