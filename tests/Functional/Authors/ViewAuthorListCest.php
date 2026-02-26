<?php

namespace Functional\Authors;

use app\tests\fixtures\AuthorsFixture;
use Codeception\Attribute\DataProvider;
use Codeception\Attribute\Group;
use Codeception\Example;
use FunctionalTester;

#[Group('functional.author')]
final readonly class ViewAuthorListCest
{
    public function _fixtures(): array
    {
        return ['authors' => AuthorsFixture::class];
    }

    #[DataProvider('filtersDataProvider')]
    #[DataProvider('sortDataProvider')]
    public function testSuccess(FunctionalTester $I, Example $example): void
    {
        $url = '/authors';

        if ($example['query_params']) {
            $url .= '?' . http_build_query($example['query_params']);
        }

        $I->amOnPage($url);
        $I->seeResponseCodeIsSuccessful();
        $I->assertGrid($example['author_ids'], $I->grabFixture('authors')->data, [
            'id',
            static fn (array $fixture): string => "{$fixture['last_name']} {$fixture['first_name']} {$fixture['middle_name']}",
            'created_at',
            'updated_at',
        ]);

        foreach ($example['errors'] ?? [] as $error) {
            $I->see($error);
        }
    }

    protected function filtersDataProvider(): array
    {
        $formName       = 'AuthorSearch';
        $defaultPageIds = range(20, 1);

        return [
            'without filters'                 => [
                'query_params' => [],
                'author_ids'   => $defaultPageIds,
            ],
            'empty filters'          => [
                'query_params' => [$formName => ['full_name' => '', 'id' => '']],
                'author_ids'   => $defaultPageIds,
            ],
            'id filter'                       => [
                'query_params' => [$formName => ['id' => '5']],
                'author_ids'   => [5],
            ],
            'full_name filter'                => [
                'query_params' => [$formName => ['full_name' => '1 ']],
                'author_ids'   => [11, 1],
            ],
            'too long full_name filter value' => [
                'query_params' => [$formName => ['full_name' => str_repeat('a', 51)]],
                'author_ids'   => $defaultPageIds,
                'errors'       => ['Full Name should contain at most 50 characters.'],
            ],
            'too big id filter value'         => [
                'query_params' => [$formName => ['id' => '2147483648']],
                'author_ids'   => $defaultPageIds,
                'errors'       => ['ID must be no greater than 2147483647.'],
            ],
            'too small id filter value'       => [
                'query_params' => [$formName => ['id' => '0']],
                'author_ids'   => $defaultPageIds,
                'errors'       => ['ID must be no less than 1.'],
            ],
        ];
    }

    protected function sortDataProvider(): array
    {
        return [
            'id sort asc'         => [
                'query_params' => ['sort' => 'id'],
                'author_ids'   => range(1, 20),
            ],
            'id sort desc'        => [
                'query_params' => ['sort' => '-id'],
                'author_ids'   => range(20, 1),
            ],
            'full_name sort asc'  => [
                'query_params' => ['sort' => 'full_name'],
                'author_ids'   => [1, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 2, 20, 3, 4, 5, 6, 7, 8, 9],
            ],
            'full_name sort desc' => [
                'query_params' => ['sort' => '-full_name'],
                'author_ids'   => [9, 8, 7, 6, 5, 4, 3, 20, 2, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 1],
            ],
        ];
    }
}
