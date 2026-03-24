<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use app\models\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;

final class AuthController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => AccessControl::class,
                'only'  => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            [
                'class'   => VerbFilter::class,
                'actions' => [
                    'login'  => ['post', 'get'],
                    'logout' => ['post'],
                ],
            ],
        ]);
    }

    public function actionLogin(Request $request): Response|string
    {
        $form = new LoginForm();

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if ($form->handle($request)) {
            \Yii::$app->user->login(User::findByUsername($form->username));

            return $this->goHome();
        }

        $form->password = '';

        return $this->render('login', ['model' => $form]);
    }

    public function actionLogout(): Response
    {
        \Yii::$app->user->logout();

        return $this->goHome();
    }
}
