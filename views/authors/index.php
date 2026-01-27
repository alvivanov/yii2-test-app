<?php

use app\models\search\AuthorSearch;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\web\View;

/**
 * @var View               $this
 * @var AuthorSearch       $searchModel
 * @var ActiveDataProvider $dataProvider
 */

$this->title                   = Yii::t('app/author', 'Authors');
$this->params['breadcrumbs'][] = $this->title;

echo Html::beginTag('div', ['class' => 'row mb-3']);
echo Html::beginTag('div', ['class' => 'd-grid d-md-flex justify-content-md-end']);
echo Html::a('Create', ['/authors/create'], ['class' => 'btn btn-success']);
echo Html::endTag('div');
echo Html::endTag('div');

echo Html::beginTag('div');
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        'id',
        'full_name',
        'created_at',
        'updated_at',
        [
            'class'    => ActionColumn::class,
            'template' => '{view} {update} {delete} {subscribe-for-new-books}',
            'buttons'  => [
                'subscribe-for-new-books' => static fn(string $url, AuthorSearch $model): string => Html::a(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark-fill" viewBox="0 0 16 16"><path d="M2 2v13.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2"/></svg>',
                    ['/authors/subscribe-for-new-books', 'id' => $model->id],
                ),
            ],
        ],
    ],
]);
echo Html::endTag('div');
