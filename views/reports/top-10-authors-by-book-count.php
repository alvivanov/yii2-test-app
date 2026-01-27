<?php

use app\models\search\AuthorSearch;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/**
 * @var View               $this
 * @var AuthorSearch       $searchModel
 * @var ActiveDataProvider $dataProvider
 */

$this->title                   = Yii::t('app/report', 'Top 10 authors by book count');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/report', 'Reports'), 'url' => ['/reports']];
$this->params['breadcrumbs'][] = $this->title;

echo Html::beginTag('div');
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => ['id', 'fullName'],
]);
echo Html::endTag('div');
