<?php

namespace app\controllers;

use app\components\book_service\BookService;
use app\components\exceptions\ModelNotFoundException;
use app\models\forms\BookForm;
use app\models\search\AuthorSearch;
use app\models\search\BookSearch;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;

final class BooksController extends Controller
{
    public function __construct(string $id, Module $module, private readonly BookService $bookService, array $config = [])
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
                'except' => ['index', 'view'],
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
                    'index'  => ['get'],
                    'view'   => ['get'],
                    'add'    => ['post', 'get'],
                    'edit'   => ['post', 'get'],
                    'delete' => ['post'],
                ],
            ],
        ]);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model'           => $this->bookService->get($id),
            'authorsDropdown' => new AuthorSearch()->searchForDropdown(),
        ]);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function actionUpdate(Request $request, int $id): Response|string
    {
        $form = $this->bookService->get($id);

        if ($form->handle($request)) {
            $this->bookService->update($form);

            return $this->redirect('/books');
        }

        return $this->render('update', ['model' => $form, 'authorsDropdown' => new AuthorSearch()->searchForDropdown()]);
    }

    public function actionCreate(Request $request): Response|string
    {
        $form = new BookForm();

        if ($form->handle($request)) {
            $this->bookService->create($form);

            return $this->redirect('/books');
        }

        return $this->render('create', ['model' => $form, 'authorsDropdown' => new AuthorSearch()->searchForDropdown()]);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function actionDelete(int $id): Response
    {
        $this->bookService->delete($id);

        return $this->redirect('/books');
    }

    public function actionIndex(Request $request): string
    {
        $searchModel = new BookSearch();

        return $this->render('index', [
            'dataProvider'     => $searchModel->search($request->queryParams),
            'searchModel'      => $searchModel,
            'availableButtons' => [
                'view'   => true,
                'update' => !\Yii::$app->user->isGuest,
                'delete' => !\Yii::$app->user->isGuest,
            ],
        ]);
    }
}
