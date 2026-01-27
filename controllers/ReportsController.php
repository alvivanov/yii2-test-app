<?php

namespace app\controllers;

use app\models\search\AuthorSearch;
use yii\filters\VerbFilter;
use yii\web\Controller;

final class ReportsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            [
                'class'   => VerbFilter::class,
                'actions' => [
                    '*' => ['get'],
                ],
            ],
        ]);
    }

    public function actionIndex(): string
    {
        return $this->render('index');
    }

    public function actionTop10AuthorsByBookCount(int $year): string
    {
        $searchModel = new AuthorSearch();

        return $this->render('top-10-authors-by-book-count', [
            'dataProvider' => $searchModel->searchTopByBookCount($year, 10),
            'searchModel'  => $searchModel,
        ]);
    }
}
