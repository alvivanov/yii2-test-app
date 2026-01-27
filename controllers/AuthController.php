<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use app\models\User;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;
use yii\web\User as UserService;

final class AuthController extends Controller
{
    public function __construct(string $id, Module $module, private readonly UserService $userService,  array $config = [])
    {
        parent::__construct($id, $module, $config);
    }



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

        if (!$this->userService->isGuest) {
            return $this->goHome();
        }

        if ($form->handle($request)) {
            $this->userService->login(User::findByUsername($form->username));

            return $this->goHome();
        }

        $form->password = '';

        return $this->render('login', ['model' => $form]);
    }

    public function actionLogout(): Response
    {
        $this->userService->logout();

        return $this->goHome();
    }
}
