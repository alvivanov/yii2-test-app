<?php

namespace app\tests\fixtures;

use app\models\Book;
use yii\test\ActiveFixture;

final class BooksFixture extends ActiveFixture
{
    public $modelClass = Book::class;
}
