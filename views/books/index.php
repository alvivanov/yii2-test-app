<?php

use app\models\search\BookSearch;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\web\View;

/**
 * @var View               $this
 * @var BookSearch         $searchModel
 * @var ActiveDataProvider $dataProvider
 */

$this->title                   = Yii::t('app/book', 'Books');
$this->params['breadcrumbs'][] = $this->title;

echo Html::beginTag('div', ['class' => 'row mb-3']);
echo Html::beginTag('div', ['class' => 'd-grid d-md-flex justify-content-md-end']);
echo Html::a('Create', ['/books/create'], ['class' => 'btn btn-success']);
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div');
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        'id',
        'title',
        'publication_year',
        'authors_string',
        'created_at',
        'updated_at',
        [
            'class'    => ActionColumn::class,
            'template' => '{view} {update} {delete}',
        ],
    ],
]);
echo Html::endTag('div');
