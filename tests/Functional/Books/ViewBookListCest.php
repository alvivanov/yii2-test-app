<?php

namespace app\tests\Functional\Books;

use app\models\Book;
use app\tests\fixtures\BookAuthorsFixture;
use Codeception\Attribute\DataProvider;
use Codeception\Attribute\Group;
use Codeception\Example;
use FunctionalTester;

#[Group('functional.books')]
final readonly class ViewBookListCest
{
    public function _fixtures(): array
    {
        return [
            'book_authors' => BookAuthorsFixture::class,
        ];
    }

    #[DataProvider('filtersDataProvider')]
    #[DataProvider('sortDataProvider')]
    public function testSuccess(FunctionalTester $I, Example $example): void
    {
        $url = '/books';

        if ($example['query_params']) {
            $url .= '?' . http_build_query($example['query_params']);
        }

        $I->amOnPage($url);
        $I->seeResponseCodeIsSuccessful();

        $I->assertGrid($example['book_ids'], Book::find()->with('authors')->all(), [
            'id',
            'title',
            'publication_year',
            static fn (Book $book): string => implode(',', array_column($book->authors, 'fullName')),
            'created_at',
            'updated_at',
        ]);

        foreach ($example['errors'] ?? [] as $error) {
            $I->see($error);
        }
    }

    protected function filtersDataProvider(): array
    {
        $formName       = 'BookSearch';
        $defaultPageIds = range(56, 37);

        return [
            'without filters'         => [
                'query_params' => [],
                'book_ids'     => $defaultPageIds,
            ],
            'empty filters'           => [
                'query_params' => [$formName => ['id' => '', 'title' => '', 'publication_year' => '', 'authors_string' => '']],
                'book_ids'     => $defaultPageIds,
            ],
            'id filter'               => [
                'query_params' => [$formName => ['id' => '5']],
                'book_ids'     => [5],
            ],
            'title filter'            => [
                'query_params' => [$formName => ['title' => 'test 1']],
                'book_ids'     => [19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 1],
            ],
            'authors_string filter'   => [
                'query_params' => [$formName => ['authors_string' => ',2 ']],
                'book_ids'     => [1],
            ],
            'publication_year filter' => [
                'query_params' => [$formName => ['publication_year' => '202']],
                'book_ids'     => $defaultPageIds,
            ],
            'too long filter values'  => [
                'query_params' => [
                    $formName => [
                        'id'               => '2147483648',
                        'title'            => str_repeat('a', 256),
                        'authors_string'   => str_repeat('a', 256),
                        'publication_year' => '10000',
                    ],
                ],
                'book_ids'     => $defaultPageIds,
                'errors'       => [
                    'ID must be no greater than 2147483647.',
                    'Title should contain at most 255 characters.',
                    'Authors should contain at most 255 characters.',
                    'Publication year must be no greater than 9999.',
                ],
            ],
            'too small filter values' => [
                'query_params' => [$formName => ['id' => '0', 'publication_year' => '0']],
                'book_ids'     => $defaultPageIds,
                'errors'       => ['ID must be no less than 1.', 'Publication year must be no less than 1.'],
            ],
        ];
    }

    protected function sortDataProvider(): array
    {
        return [
            'id sort asc'                => [
                'query_params' => ['sort' => 'id'],
                'book_ids'     => range(1, 20),
            ],
            'id sort desc'               => [
                'query_params' => ['sort' => '-id'],
                'book_ids'     => range(56, 37),
            ],
            'title sort asc'             => [
                'query_params' => ['sort' => 'title'],
                'book_ids'     => [1, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 2, 20, 21, 22, 23, 24, 25, 26, 27],
            ],
            'title sort desc'            => [
                'query_params' => ['sort' => '-title'],
                'book_ids'     => [9, 8, 7, 6, 56, 55, 54, 53, 52, 51, 50, 5, 49, 48, 47, 46, 45, 44, 43, 42],
            ],
            'publication_year sort asc'  => [
                'query_params' => ['sort' => 'publication_year'],
                'book_ids'     => [56, ...range(1, 19)],
            ],
            'publication_year sort desc' => [
                'query_params' => ['sort' => '-publication_year'],
                'book_ids'     => range(55, 36),
            ],
            //            'author_string sort asc' => [
            //                'query_params' => ['sort' => 'author_string'],
            //                'book_ids'     => range(1, 20),
            //            ],
            //            'author_string sort desc' => [
            //                'query_params' => ['sort' => '-author_string'],
            //                'book_ids'     => range(55, 36),
            //            ],
        ];
    }
}
