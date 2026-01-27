<?php

namespace app\models;

use Yii;
use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * @property int    id
 * @property string username
 * @property string password
 * @property string created_at
 * @property string updated_at
 */
final class User extends ActiveRecord implements IdentityInterface
{
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
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ]);
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['username', 'password'], 'required'],
            [['username', 'password'], 'string', 'length' => [6, 255]],
            [['created_at', 'updated_at'], 'date'],
        ]);
    }

    public static function findByUsername(string $username): ?self
    {
        return self::findOne(['username' => $username]);
    }

    /**
     * @inheritDoc
     */
    public static function findIdentity($id): ?self
    {
        return self::findOne($id);
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($token, $type = null): null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'id'         => Yii::t('app/user', 'ID'),
            'username'   => Yii::t('app/user', 'Username'),
            'password'   => Yii::t('app/user', 'Password'),
            'created_at' => Yii::t('app/user', 'Created At'),
            'updated_at' => Yii::t('app/user', 'Updated At'),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey(): null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey): null
    {
        return null;
    }
}
