<?php

use app\models\Author;
use yii\bootstrap5\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var View       $this
 * @var Author     $model
 * @var bool       $readOnly
 * @var bool       $isCreate
 * @var ActiveForm $form
 */

$readOnly ??= false;
$isCreate ??= false;

echo Html::beginTag('div');
echo Html::tag('h1', $this->title);
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-md-6']);

$form = ActiveForm::begin(['id' =>'AuthorForm']);

echo Html::beginTag('div', ['class' => 'form-group']);
echo $form->field($model, 'first_name')->textInput(['disabled' => $readOnly]);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-group']);
echo $form->field($model, 'last_name')->textInput(['disabled' => $readOnly]);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-group']);
echo $form->field($model, 'middle_name')->textInput(['disabled' => $readOnly]);
echo Html::endTag('div');

if (!$readOnly) {
    echo Html::beginTag('div', ['class' => 'form-group']);
    echo Html::submitButton(
        $isCreate ? Yii::t('app', 'Add') : Yii::t('app', 'Edit'),
        ['class' => $isCreate ? 'btn btn-success' : 'btn btn-primary']
    );
    echo Html::endTag('div');
}

ActiveForm::end();

echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
