<?php

namespace app\models\forms;

use app\models\AuthorSubscription;
use borales\extensions\phoneInput\PhoneInputValidator;
use Yii;
use yii\base\Model;
use yii\behaviors\AttributeTypecastBehavior;
use yii\web\Request;

final class NewBookSubscriptionForm extends Model
{
    /** @var string */
    public mixed $phone = null;
    /** @var int */
    public mixed $author_id = null;

    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [AttributeTypecastBehavior::class]);
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['phone', 'author_id'], 'required'],
            [['phone'], PhoneInputValidator::class],
            [
                ['phone', 'author_id'],
                'unique',
                'targetClass'     => AuthorSubscription::class,
                'targetAttribute' => ['phone', 'author_id'],
                'message'         => Yii::t('app/author_subscription', 'This phone number has already been taken.'),
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'phone' => Yii::t('app/author_subscription', 'Phone'),
        ]);
    }

    public function handle(Request $request): bool
    {
        return $this->load($request->post()) && $this->validate();
    }
}
