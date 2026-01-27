<?php

/**
 * @var View                    $this
 * @var AuthorForm              $author
 * @var NewBookSubscriptionForm $form
 */

use app\models\forms\AuthorForm;
use app\models\forms\NewBookSubscriptionForm;
use borales\extensions\phoneInput\PhoneInput;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\web\View;

$this->title                   = Yii::t('app/author', 'Subscribe for new books');
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['/authors']];
$this->params['breadcrumbs'][] = ['label' => $author->fullName, 'url' => ['/authors/view', 'id' => $author->id]];
$this->params['breadcrumbs'][] = $this->title;

echo Html::beginTag('div');
echo Html::tag('h1', Yii::t('app/author', $author->fullName));
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div');
echo Html::tag('p', $this->title);
echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-md-6']);

$activeForm = ActiveForm::begin(['id' => 'subscribe-form']);
echo Html::beginTag('div', ['class' => 'form-group']);
echo $activeForm->field($form, 'phone')->widget(PhoneInput::class);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-group']);
echo Html::submitButton(Yii::t('app/author', 'Subscribe'), ['class' => 'btn btn-success']);
echo Html::endTag('div');

ActiveForm::end();

echo Html::endTag('div');
echo Html::endTag('div');
echo Html::endTag('div');
