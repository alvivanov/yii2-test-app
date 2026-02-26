<?php

namespace app\tests\Functional\Books;

use app\components\smspilot_api_client\SmspilotApiClientInterface;
use app\models\Book;
use app\models\BookAuthor;
use app\models\User;
use app\tests\fixtures\AuthorsFixture;
use app\tests\fixtures\AuthorSubscriptionsFixture;
use app\tests\fixtures\UsersFixture;
use Codeception\Attribute\DataProvider;
use Codeception\Attribute\Group;
use Codeception\Example;
use Codeception\Stub;
use FunctionalTester;
use Yii;

#[Group('functional.books')]
final class CreateBookCest
{
    public function _fixtures(): array
    {
        return [
            'users'                => UsersFixture::class,
            'authors'              => AuthorsFixture::class,
            'author_subscriptions' => AuthorSubscriptionsFixture::class,
        ];
    }

    public function testPageAuth(FunctionalTester $I): void
    {
        $I->amOnPage('/books/create');
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/auth/login');
    }

    public function testSuccess(FunctionalTester $I): void
    {
        $authors  = [
            $authorsWithSubscription = $I->grabFixture('authors', 'author_with_subscription'),
            $I->grabFixture('authors', 'author_without_subscription_2'),
        ];

        $bookData = [
            'title'            => 'test1',
            'publication_year' => 2026,
            'isbn'             => str_repeat('a', 13),
        ];

        Yii::$container->set(SmspilotApiClientInterface::class, static fn () => Stub::makeEmpty(SmspilotApiClientInterface::class, [
            'send' => Stub\Expected::once(function (string $phone, string $message) use ($I, $authorsWithSubscription, $bookData): void {
                $I->assertEquals(
                    sprintf(
                        '%s %s %s published new book "%s"',
                        $authorsWithSubscription['last_name'],
                        $authorsWithSubscription['first_name'],
                        $authorsWithSubscription['middle_name'],
                        $bookData['title']
                    ),
                    $message
                );
                $I->assertEquals($I->grabFixture('author_subscriptions', '0')['phone'], $phone);
            }),
        ]));

        $I->amLoggedInAs($I->grabRecord(User::class));
        $I->amOnPage('/books/create');
        $I->seeResponseCodeIsSuccessful();
        $I->attachFile('#bookform-main_page_image', 'test_image.png');
        $I->submitForm('#BookForm', ['BookForm' => ['authors' => array_column($authors, 'id'), ...$bookData]]);
        $I->canSeeCurrentUrlEquals('/books');
        $I->canSeeRecord(Book::class, $bookData);
        $book = $I->grabRecord(Book::class, $bookData);

        foreach ($authors as $author) {
            $I->canSeeRecord(BookAuthor::class, ['author_id' => $author['id'], 'book_id' => $book->getPrimaryKey()]);
        }
    }

    #[DataProvider('validationDataProvider')]
    public function testValidationRules(FunctionalTester $I, Example $example): void
    {
        $I->amLoggedInAs($I->grabRecord(User::class));
        $I->amOnPage('/books/create');
        $I->seeResponseCodeIsSuccessful();

        if ($file = $example['main_page_image']) {
            $I->attachFile('#bookform-main_page_image', $file);
        }

        $I->submitForm('#BookForm', ['BookForm' => [...$example['book_data'], 'authors' => $example['authors']]]);
        $I->canSeeInCurrentUrl('/books/create');
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
            'check fields max length' => [
                'book_data'       => [
                    'title'            => str_repeat('a', 256),
                    'isbn'             => str_repeat('a', 14),
                    'publication_year' => 10000,
                ],
                'main_page_image' => 'test_image.png',
                'authors'         => [1, 2],
                'errors'          => [
                    'Title should contain at most 255 characters.',
                    'ISBN should contain 13 characters.',
                    'Publication year must be no greater than 9999.',
                ],
            ],
            'check fields min length' => [
                'book_data'       => [
                    'title'            => str_repeat('a', 255),
                    'isbn'             => str_repeat('a', 12),
                    'publication_year' => -1,
                ],
                'main_page_image' => 'test_image.png',
                'authors'         => [1, 2],
                'errors'          => [
                    'ISBN should contain 13 characters.',
                    'Publication year must be no less than 0.',
                ],
            ],
            'invalid author ids'      => [
                'book_data'       => [
                    'title'            => str_repeat('a', 255),
                    'isbn'             => str_repeat('a', 13),
                    'publication_year' => 1212,
                ],
                'main_page_image' => 'test_image.png',
                'authors'         => [123123],
                'errors'          => [
                    'Authors is invalid.',
                ],
            ],
        ];
    }
}
