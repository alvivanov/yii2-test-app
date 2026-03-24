<?php

namespace app\controllers;

use app\components\author_service\AuthorService;
use app\components\exceptions\ModelNotFoundException;
use app\models\forms\AuthorForm;
use app\models\forms\NewBookSubscriptionForm;
use app\models\search\AuthorSearch;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;

final class AuthorsController extends Controller
{
    public function __construct(string $id, Module $module, private readonly AuthorService $authorService, array $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            [
                'class'  => AccessControl::class,
                'except' => ['index', 'view', 'subscribe-for-new-books'],
                'rules'  => [
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            [
                'class'   => VerbFilter::class,
                'actions' => [
                    '*'                       => ['get'],
                    'create'                  => ['post', 'get'],
                    'update'                  => ['post', 'get'],
                    'subscribe-for-new-books' => ['post', 'get'],
                    'delete'                  => ['post'],
                ],
            ],
        ]);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function actionView(int $id): string
    {
        return $this->render('view', ['model' => $this->authorService->get($id)]);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function actionUpdate(int $id, Request $request): Response|string
    {
        $form = $this->authorService->get($id);

        if ($form->handle($request)) {
            $this->authorService->update($id, $form);

            return $this->redirect('index');
        }

        return $this->render('update', ['model' => $form]);
    }

    public function actionCreate(Request $request): Response|string
    {
        $form = new AuthorForm();

        if ($form->handle($request)) {
            $this->authorService->create($form);

            return $this->redirect('index');
        }

        return $this->render('update', ['model' => $form]);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function actionDelete(int $id): Response
    {
        $this->authorService->delete($id);

        return $this->redirect('/authors');
    }

    /**
     * @throws ModelNotFoundException
     */
    public function actionSubscribeForNewBooks(Request $request, int $id): Response|string
    {
        $form = new NewBookSubscriptionForm(['author_id' => $id]);

        if ($form->handle($request)) {
            $this->authorService->createSubscriptionForNewBooks($id, $form);

            return $this->redirect('/authors');
        }

        return $this->render('subscribe-for-new-books', ['author' => $this->authorService->get($id), 'form' => $form]);
    }

    public function actionIndex(Request $request): string
    {
        $searchModel = new AuthorSearch();

        return $this->render('index', [
            'dataProvider'     => $searchModel->search($request->queryParams),
            'searchModel'      => $searchModel,
            'availableButtons' => [
                'view'                    => true,
                'subscribe-for-new-books' => true,
                'update'                  => !\Yii::$app->user->isGuest,
                'delete'                  => !\Yii::$app->user->isGuest,
            ],
        ]);
    }
}
