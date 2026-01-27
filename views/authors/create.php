<?php

/**
 * @var View       $this
 * @var AuthorForm $model
 */

use app\models\forms\AuthorForm;
use yii\web\View;

$this->title                   = Yii::t('app/author', 'Create author');
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['/authors']];
$this->params['breadcrumbs'][] = $this->title;

echo $this->render('_form', ['model' => $model, 'isCreate' => true]);
