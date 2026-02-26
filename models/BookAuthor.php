<?php

namespace app\models;

use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int    book_id
 * @property int    author_id
 * @property string created_at
 */
final class BookAuthor extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            AttributeTypecastBehavior::class,
            ['class' => TimestampBehavior::class, 'value' => date('Y-m-d H:i:s'), 'updatedAtAttribute' => false],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::class],
            [['created_at'], 'date'],
        ]);
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    public function getBook(): ActiveQuery
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }
}
