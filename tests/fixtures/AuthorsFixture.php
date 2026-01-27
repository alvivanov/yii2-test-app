<?php

namespace app\tests\fixtures;

use app\models\Author;
use yii\test\ActiveFixture;

final class AuthorsFixture extends ActiveFixture
{
    public $modelClass = Author::class;
}
