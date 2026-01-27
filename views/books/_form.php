<?php

/**
 * @var View $this
 * @var BookForm $model
 */

use app\models\Author;
use app\models\forms\BookForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;

$readOnly ??= false;
$isCreate ??= false;

echo Html::beginTag('div');
echo Html::tag('h1', $this->title);
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-md-6']);

$form = ActiveForm::begin(['id' => 'BookForm']);

echo Html::beginTag('div', ['class' => 'form-group col-md-6']);
echo $form->field($model, 'title')->textInput(['disabled' => $readOnly]);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-group col-md-6']);
echo $form->field($model, 'publication_year')->textInput(['disabled' => $readOnly, 'type' => 'number']);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-group col-md-6']);
echo $form->field($model, 'isbn')->textInput(['disabled' => $readOnly]);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-group col-md-6']);
echo $form->field($model, 'main_page_image')->fileInput(['disabled' => $readOnly]);
echo Html::endTag('div');

if ($model->main_page_image_url) {
    echo Html::beginTag('div', ['class' => 'form-group col-md-6']);
    echo Html::img($model->main_page_image_url);
    echo Html::endTag('div');
}

echo Html::beginTag('div', ['class' => 'form-group col-md-6']);
echo $form->field($model, 'authors')->dropDownList(ArrayHelper::map(Author::find()->all(), 'id', 'fullName'), [
    'multiple' => true,
]);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-group col-md-12']);
echo Html::submitButton(
    $isCreate ? Yii::t('app', 'Add') : Yii::t('app', 'Edit'),
    ['class' => $isCreate ? 'btn btn-success' : 'btn btn-primary']
);
echo Html::endTag('div');

ActiveForm::end();

echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
