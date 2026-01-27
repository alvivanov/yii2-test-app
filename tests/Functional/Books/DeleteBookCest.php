<?php

namespace app\tests\Functional\Books;

use app\models\Book;
use app\models\BookAuthor;
use app\models\User;
use app\tests\fixtures\BooksFixture;
use app\tests\fixtures\UsersFixture;
use Codeception\Attribute\Group;
use FunctionalTester;

#[Group('functional.books')]
final readonly class DeleteBookCest
{
    public function _fixtures(): array
    {
        return [
            'users' => UsersFixture::class,
            'books' => BooksFixture::class,
        ];
    }

    public function testPageAuth(FunctionalTester $I): void
    {
        $bookId = $I->grabRecord(Book::class)->getPrimaryKey();

        $I->amOmPagePost("/books/$bookId/delete");
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/auth/login');
    }

    public function testPageNotFound(FunctionalTester $I): void
    {
        $I->amLoggedInAs($I->grabRecord(User::class));
        $I->amOmPagePost('/books/123123123/delete');
        $I->seePageNotFound();
    }

    public function testSuccess(FunctionalTester $I): void
    {
        $bookId = $I->grabRecord(Book::class)->getPrimaryKey();

        $I->amLoggedInAs($I->grabRecord(User::class));
        $I->amOmPagePost("/books/$bookId/delete");
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/books');
        $I->dontSeeRecord(Book::class, ['id' => $bookId]);
        $I->dontSeeRecord(BookAuthor::class, ['book_id' => $bookId]);
    }
}
