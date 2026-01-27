<?php

namespace app\models;

use tuyakhov\notifications\NotifiableInterface;
use tuyakhov\notifications\NotifiableTrait;
use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property int         id
 * @property string      phone
 * @property string      author_id
 * @property string      created_at
 *
 * @property-read Author author
 */
final class AuthorSubscription extends ActiveRecord implements NotifiableInterface
{
    use NotifiableTrait;

    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => AttributeTypecastBehavior::class,
            ],
            [
                'class'              => TimestampBehavior::class,
                'value'              => new Expression('NOW()'),
                'updatedAtAttribute' => false,
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['phone', 'author_id'], 'required'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
            [['created_at'], 'date'],
        ]);
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    public function routeNotificationForSms(): string
    {
        return $this->phone;
    }

    /**
     * @inheritDoc
     */
    public function viaChannels(): array
    {
        return ['sms'];
    }
}
