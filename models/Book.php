<?php

namespace app\models;

use diecoding\flysystem\traits\ModelTrait;
use Yii;
use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\OptimisticLockBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int               id
 * @property string            title
 * @property int               publication_year
 * @property string            isbn
 * @property string            main_page_image
 * @property string            created_at
 * @property string            updated_at
 *
 * @property-read BookAuthor[] bookAuthors
 * @property-read Author[]     authors
 */
class Book extends ActiveRecord
{
    use ModelTrait;

    public const string BOOK_ADDED_EVENT = 'event_book_added';

    /**
     * @inheritDoc
     */
    public static function tableName(): string
    {
        return '{{%book}}';
    }

    /**
     * @inheritDoc
     */
    protected function attributePaths(): array
    {
        return [
            'main_page_image' => "/books/$this->id",
        ];
    }

    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            OptimisticLockBehavior::class,
            AttributeTypecastBehavior::class,
            ['class' => TimestampBehavior::class, 'value' => date('Y-m-d H:i:s')],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function optimisticLock(): string
    {
        return 'version';
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['title', 'publication_year', 'isbn', 'main_page_image'], 'required'],
            [['title', 'isbn', 'main_page_image'], 'string', 'max' => 255],
            [['publication_year'], 'integer', 'min' => 0, 'max' => 9999],
            [['created_at', 'updated_at'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'id'               => Yii::t('app/book', 'ID'),
            'title'            => Yii::t('app/book', 'Title'),
            'publication_year' => Yii::t('app/book', 'Publication year'),
            'isbn'             => Yii::t('app/book', 'ISBN'),
            'main_page_image'  => Yii::t('app/book', 'Main page image'),
            'created_at'       => Yii::t('app/book', 'Created At'),
            'updated_at'       => Yii::t('app/book', 'Updated At'),
        ]);
    }

    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])->via('bookAuthors');
    }

    public function getBookAuthors(): ActiveQuery
    {
        return $this->hasMany(BookAuthor::class, ['book_id' => 'id']);
    }
}
