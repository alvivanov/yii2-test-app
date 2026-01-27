<?php

namespace app\components\book_service;

use app\components\exceptions\ModelNotFoundException;
use app\models\Author;
use app\models\Book;
use app\models\BookAuthor;
use app\models\forms\BookForm;
use Yii;
use yii\helpers\ArrayHelper;

final readonly class BookService
{
    /**
     * @throws ModelNotFoundException
     */
    public function get(int $id): BookForm
    {
        return BookForm::createFromModel($this->getBook($id));
    }

    /**
     * @throws ModelNotFoundException
     */
    private function getBook(int $id): Book
    {
        return Book::findOne($id) ?? throw new ModelNotFoundException();
    }

    public function create(BookForm $form): void
    {
        Yii::$app->db->transaction(function () use ($form): void {
            $book = new Book([
                'title'            => $form->title,
                'publication_year' => $form->publication_year,
                'isbn'             => $form->isbn,
            ]);
            $book->saveUploadedFile($form->main_page_image, 'main_page_image');
            $book->save();
            $this->syncAuthors($form, $book);
            $book->trigger(Book::BOOK_ADDED_EVENT);
        });
    }

    private function syncAuthors(BookForm $form, Book $book): void
    {
        BookAuthor::deleteAll(['author_id' => ArrayHelper::getColumn($book->authors, 'id')]);

        foreach (Author::findAll($form->authors) as $author) {
            $book->link('authors', $author);
        }
    }

    /**
     * @throws ModelNotFoundException
     */
    public function delete(int $id): void
    {
        Yii::$app->db->transaction(function () use ($id): void {
            $book = $this->getBook($id);
            BookAuthor::deleteAll(['book_id' => $book->id]);
            $book->delete();
            $book->removeFile('main_page_image');
        });
    }

    /**
     * @throws ModelNotFoundException
     */
    public function update(BookForm $form): void
    {
        Yii::$app->db->transaction(function () use ($form): void {
            $book = $this->getBook($form->id);
            $book->setAttributes([
                'title'            => $form->title,
                'publication_year' => $form->publication_year,
                'isbn'             => $form->isbn,
            ]);

            if ($form->main_page_image) {
                $book->saveUploadedFile($form->main_page_image, 'main_page_image', 'main_page_image');
            }

            $book->save();
            $this->syncAuthors($form, $book);
        });
    }
}
