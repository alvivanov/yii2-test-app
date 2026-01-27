<?php

namespace app\tests\Functional\Books;

use app\models\Book;
use app\models\BookAuthor;
use app\models\User;
use app\tests\fixtures\AuthorsFixture;
use app\tests\fixtures\BookAuthorsFixture;
use app\tests\fixtures\BooksFixture;
use app\tests\fixtures\UsersFixture;
use Codeception\Attribute\DataProvider;
use Codeception\Attribute\Group;
use Codeception\Example;
use FunctionalTester;

#[Group('functional.books')]
final readonly class UpdateBookCest
{
    public function _fixtures(): array
    {
        return [
            'users'        => UsersFixture::class,
            'authors'      => AuthorsFixture::class,
            'books'        => BooksFixture::class,
            'book_authors' => BookAuthorsFixture::class,
        ];
    }

    public function testPageAuth(FunctionalTester $I): void
    {
        $bookId = $I->grabRecord(Book::class)->getPrimaryKey();

        $I->amOnPage("/books/$bookId/update");
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/auth/login');
    }

    public function testPageNotFound(FunctionalTester $I): void
    {
        $I->amLoggedInAs($I->grabRecord(User::class));
        $I->amOnPage('/books/123123123/update');
        $I->seePageNotFound();
    }

    public function testSuccess(FunctionalTester $I): void
    {
        $book               = $I->grabRecord(Book::class);
        $bookId             = $book->getPrimaryKey();
        $currentBookAuthors = $book->getRelation('authors')->all();
        $newBooksAuthorIds  = [5, 6];

        $bookData = [
            'title'            => 'test1',
            'publication_year' => 2026,
            'isbn'             => str_repeat('a', 13),
        ];

        $I->amLoggedInAs($I->grabRecord(User::class));
        $I->amOnPage("/books/$bookId/update");
        $I->seeResponseCodeIsSuccessful();
        $I->attachFile('#bookform-main_page_image', 'test_image.png');
        $I->submitForm('#BookForm', ['BookForm' => ['authors' => $newBooksAuthorIds, ...$bookData]]);
        $I->amOnPage("/books/$bookId/update");
        $I->canSeeRecord(Book::class, ['id' => $bookId, ...$bookData]);
        $I->canSeeCurrentUrlEquals("/books");

        foreach ($currentBookAuthors as $currentBookAuthor) {
            $I->cantSeeRecord(BookAuthor::class, ['author_id' => $currentBookAuthor->id, 'book_id' => $bookId]);
        }

        foreach ($newBooksAuthorIds as $newBooksAuthorId) {
            $I->canSeeRecord(BookAuthor::class, ['author_id' => $newBooksAuthorId, 'book_id' => $bookId]);
        }
    }

    #[DataProvider('validationDataProvider')]
    #[Group('test')]
    public function testValidationRules(FunctionalTester $I, Example $example): void
    {
        $bookId = $I->grabRecord(Book::class)->getPrimaryKey();

        $I->amLoggedInAs($I->grabRecord(User::class));
        $I->amOnPage("/books/$bookId/update");
        $I->seeResponseCodeIsSuccessful();

        if ($file = $example['main_page_image']) {
            $I->attachFile('#bookform-main_page_image', $file);
        }

        $I->submitForm('#BookForm', ['BookForm' => [...$example['book_data'], 'authors' => $example['authors']]]);
        $I->canSeeCurrentUrlEquals("/books/$bookId/update");
        $I->cantSeeRecord(Book::class, $example['book_data']);

        foreach ($example['errors'] as $error) {
            $I->see($error, '.help-block');
        }
    }

    protected function validationDataProvider(): array
    {
        return [
            'all fields are required' => [
                'book_data'       => [
                    'title'            => '',
                    'publication_year' => '',
                    'isbn'             => '',
                ],
                'main_page_image' => null,
                'authors'         => [],
                'errors'          => [
                    'Title cannot be blank.',
                    'Publication year cannot be blank.',
                    'Isbn cannot be blank.',
                    'Authors cannot be blank',
                    'Main page image cannot be blank',
                ],
            ],
//            'check fields max length' => [
//                'book_data'       => [
//                    'title'            => str_repeat('a', 256),
//                    'isbn'             => str_repeat('a', 14),
//                    'publication_year' => 10000,
//                ],
//                'main_page_image' => 'test_image.png',
//                'authors'         => [1, 2],
//                'errors'          => [
//                    'Title should contain at most 255 characters.',
//                    'ISBN should contain 13 characters.',
//                    'Publication year must be no greater than 9999.',
//                ],
//            ],
//            'check fields min length' => [
//                'book_data'       => [
//                    'title'            => str_repeat('a', 255),
//                    'isbn'             => str_repeat('a', 12),
//                    'publication_year' => -1,
//                ],
//                'main_page_image' => 'test_image.png',
//                'authors'         => [1, 2],
//                'errors'          => [
//                    'ISBN should contain 13 characters.',
//                    'Publication year must be no less than 0.',
//                ],
//            ],
//            'invalid author ids'      => [
//                'book_data'       => [
//                    'title'            => str_repeat('a', 255),
//                    'isbn'             => str_repeat('a', 13),
//                    'publication_year' => 1212,
//                ],
//                'main_page_image' => 'test_image.png',
//                'authors'         => [123123],
//                'errors'          => [
//                    'Authors is invalid.',
//                ],
//            ],
        ];
    }
}
