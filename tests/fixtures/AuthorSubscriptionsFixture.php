<?php

namespace app\tests\fixtures;

use app\models\Author;
use app\models\AuthorSubscription;
use app\tests\fixtures\AuthorsFixture;
use yii\test\ActiveFixture;

final class AuthorSubscriptionsFixture extends ActiveFixture
{
    public $modelClass = AuthorSubscription::class;
    public $depends    = [AuthorsFixture::class];
}
