<?php

/**
 * @var View   $this
 * @var AuthorForm $model
 */

use app\models\forms\AuthorForm;
use yii\web\View;

$this->title                   = Yii::t('app/author', 'Update author');
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['/authors']];
$this->params['breadcrumbs'][] = $model->fullName;

echo $this->render('_form', ['model' => $model]);
