<?php

namespace app\tests\Functional\Reports;

use app\tests\fixtures\AuthorsFixture;
use app\tests\fixtures\BookAuthorsFixture;
use app\tests\fixtures\BooksFixture;
use Codeception\Attribute\DataProvider;
use Codeception\Attribute\Group;
use Codeception\Example;
use FunctionalTester;

#[Group('functional.reports')]
final readonly class Top10AuthorsByBookCountCest
{
    public function _fixtures(): array
    {
        return [
            'authors'      => AuthorsFixture::class,
            'books'        => BooksFixture::class,
            'book_authors' => BookAuthorsFixture::class,
        ];
    }

    public function testInvalidYear(FunctionalTester $I): void
    {
        $I->amOnPage('/reports/top-10-authors-by-book-count/invalid_year');
        $I->seePageNotFound();
    }

    #[DataProvider('successDataProvider')]
    public function testSuccess(FunctionalTester $I, Example $example): void
    {
        $I->amOnPage("/reports/top-10-authors-by-book-count/{$example['year']}");
        $I->seeResponseCodeIsSuccessful();
        $I->assertGrid($example['author_ids'], $I->grabFixture('authors')->data, [
            'id',
            static fn(array $fixture): string => "{$fixture['last_name']} {$fixture['first_name']} {$fixture['middle_name']}",
        ]);
    }

    protected function successDataProvider(): array
    {
        return [
            ['year' => 2024, 'author_ids' => []],
            ['year' => 2025, 'author_ids' => [11]],
            ['year' => 2026, 'author_ids' => [11, 10, 9, 8, 7, 6, 5, 4, 3, 1]],
        ];
    }
}
