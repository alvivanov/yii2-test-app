<?php

namespace app\tests\Functional\Books;

use app\models\Book;
use app\tests\fixtures\BooksFixture;
use Codeception\Attribute\Group;
use FunctionalTester;

#[Group('functional.books')]
final readonly class ViewBookCest
{
    public function _fixtures(): array
    {
        return ['books' => BooksFixture::class];
    }

    public function testPageNotFound(FunctionalTester $I): void
    {
        $I->amOnPage('/books/123123123');
        $I->seePageNotFound();
    }

    public function testSuccess(FunctionalTester $I): void
    {
        $bookId = $I->grabRecord(Book::class)->getPrimaryKey();

        $I->amOnPage("/books/$bookId");
        $I->seeResponseCodeIsSuccessful();
    }
}
