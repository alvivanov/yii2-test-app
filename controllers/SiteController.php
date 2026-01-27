<?php

namespace app\controllers;

use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ErrorAction;

final class SiteController extends Controller
{
    /**
     * @inheritdoc
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

    /**
     * @inheritdoc
     */
    public function actions(): array
    {
        return [
            'error' => ErrorAction::class,
        ];
    }

    public function actionIndex(): string
    {
        return $this->render('index');
    }
}
