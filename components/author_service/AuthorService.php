<?php

namespace app\components\author_service;

use app\components\exceptions\ModelNotFoundException;
use app\models\Author;
use app\models\AuthorSubscription;
use app\models\forms\AuthorForm;
use app\models\forms\NewBookSubscriptionForm;
use Yii;

final class AuthorService
{
    /**
     * @throws ModelNotFoundException
     */
    public function get(int $id): AuthorForm
    {
        return AuthorForm::createFromModel($this->getAuthor($id));
    }

    /**
     * @throws ModelNotFoundException
     */
    private function getAuthor(int $id): Author
    {
        return Author::findOne($id) ?? throw new ModelNotFoundException();
    }

    public function create(AuthorForm $form): void
    {
        $author = new Author([
            'first_name'  => $form->first_name,
            'last_name'   => $form->last_name,
            'middle_name' => $form->middle_name,
        ]);

        $author->save();
    }

    /**
     * @throws ModelNotFoundException
     */
    public function update(int $id, AuthorForm $form): void
    {
        $author = $this->getAuthor($id);
        $author->setAttributes([
            'first_name'  => $form->first_name,
            'last_name'   => $form->last_name,
            'middle_name' => $form->middle_name,
        ]);

        $author->save();
    }

    /**
     * @throws ModelNotFoundException
     */
    public function createSubscriptionForNewBooks(int $id, NewBookSubscriptionForm $form): void
    {
        $subscription = new AuthorSubscription(['phone' => $form->phone]);

        $this->getAuthor($id)->link('subscriptions', $subscription);
    }

    /**
     * @throws ModelNotFoundException|\Throwable
     */
    public function delete(int $id): void
    {
        Yii::$app->db->transaction(function () use ($id): void {
            $author = $this->getAuthor($id);

            if ($author->getBooks()->exists()) {
                throw new \DomainException();
            }

            AuthorSubscription::deleteAll(['author_id' => $author->id]);
            $author->delete();
        });
    }
}
