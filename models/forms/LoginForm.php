<?php

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\web\Request;

final class LoginForm extends Model
{
    /** @var string */
    public mixed $username = null;
    /** @var string */
    public mixed $password = null;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['username', 'password'], 'required'],
            [['username', 'password'], 'string', 'length' => [6, 255]],
            [['password'], 'validatePassword'],
        ];
    }

    public function validatePassword(string $attribute): void
    {
        $user = User::findByUsername($this->username);

        if (!$this->hasErrors() && (!$user || !Yii::$app->security->validatePassword($this->password, $user->password))) {
            $this->addError($attribute, 'Incorrect username or password.');
        }
    }

    public function handle(Request $request): bool
    {
        return $this->load($request->post()) && $this->validate();
    }
}
