<?php

namespace app\tests\fixtures;

use app\models\BookAuthor;
use yii\test\ActiveFixture;

final class BookAuthorsFixture extends ActiveFixture
{
    public $modelClass = BookAuthor::class;
    public $depends = [BooksFixture::class, AuthorsFixture::class];
}
