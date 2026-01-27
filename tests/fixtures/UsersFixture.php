<?php

namespace app\tests\fixtures;

use app\models\User;
use yii\test\ActiveFixture;

final class UsersFixture extends ActiveFixture
{
    public $modelClass = User::class;
}
