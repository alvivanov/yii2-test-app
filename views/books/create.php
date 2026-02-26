<?php

/**
 * @var View     $this
 * @var BookForm $model
 * @var array    $authorsDropdown
 */

use app\models\forms\BookForm;
use yii\web\View;

$this->title = Yii::t('app/book', 'Add new book');

echo $this->render('_form', ['model' => $model, 'authorsDropdown' => $authorsDropdown, 'isCreate' => true]);
