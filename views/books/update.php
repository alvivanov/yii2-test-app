<?php

/**
 * @var View     $this
 * @var BookForm $model
 * @var array    $authorsDropdown
 */

use app\models\forms\BookForm;
use yii\web\View;

$this->title                   = Yii::t('app/book', 'Update book');
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['/books']];
$this->params['breadcrumbs'][] = $model->title;

echo $this->render('_form', ['model' => $model, 'authorsDropdown' => $authorsDropdown]);
