<?php

namespace app\models;

use Yii;
use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\OptimisticLockBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int                       id
 * @property string                    first_name
 * @property string                    last_name
 * @property string                    middle_name
 * @property string                    created_at
 * @property string                    updated_at
 *
 * @property-read string               fullName
 * @property-read Book[]               books
 * @property-read AuthorSubscription[] subscriptions
 */
class Author extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName(): string
    {
        return '{{%author}}';
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
            [['first_name', 'last_name', 'middle_name'], 'required'],
            [['first_name', 'last_name', 'middle_name'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'id'          => Yii::t('app/author', 'ID'),
            'first_name'  => Yii::t('app/author', 'First name'),
            'last_name'   => Yii::t('app/author', 'Last name'),
            'middle_name' => Yii::t('app/author', 'Middle name'),
            'created_at'  => Yii::t('app/author', 'Created At'),
            'updated_at'  => Yii::t('app/author', 'Updated At'),
        ]);
    }

    public function getBooks(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])->via('bookAuthors');
    }

    public function getBookAuthors(): ActiveQuery
    {
        return $this->hasMany(BookAuthor::class, ['author_id' => 'id']);
    }

    public function getSubscriptions(): ActiveQuery
    {
        return $this->hasMany(AuthorSubscription::class, ['author_id' => 'id']);
    }

    public function getFullName(): string
    {
        return "$this->last_name $this->first_name $this->middle_name";
    }
}
