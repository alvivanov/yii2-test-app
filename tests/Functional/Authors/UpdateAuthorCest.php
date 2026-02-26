<?php

namespace Functional\Authors;

use app\models\Author;
use app\models\User;
use app\tests\fixtures\AuthorsFixture;
use app\tests\fixtures\UsersFixture;
use Codeception\Attribute\DataProvider;
use Codeception\Attribute\Group;
use Codeception\Example;
use FunctionalTester;

#[Group('functional.authors')]
final readonly class UpdateAuthorCest
{
    public function _fixtures(): array
    {
        return [
            'users'   => UsersFixture::class,
            'authors' => AuthorsFixture::class,
        ];
    }

    public function testPageAuth(FunctionalTester $I): void
    {
        $authorId = $I->grabRecord(Author::class)->getPrimaryKey();
        $I->amOnPage("/authors/$authorId/update");
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/auth/login');
    }

    public function testPageNotFound(FunctionalTester $I): void
    {
        $I->amLoggedInAs($I->grabRecord(User::class));
        $I->amOnPage('/authors/123123123/update');
        $I->seePageNotFound();
    }

    public function testSuccess(FunctionalTester $I): void
    {
        $authorId   = $I->grabRecord(Author::class)->getPrimaryKey();
        $authorData = [
            'first_name'  => 'test1',
            'last_name'   => 'test2',
            'middle_name' => 'test3',
        ];

        $I->amLoggedInAs($I->grabRecord(User::class));
        //        $I->amOnPage("/authors/$authorId/update");
        //        $I->seeResponseCodeIsSuccessful();
        //        $I->submitForm('#AuthorForm', ['AuthorForm' => $authorData]);
        //        $I->canSeeRecord(Author::class, ['id' => $authorId, ...$authorData]);
        //        $I->canSeeInCurrentUrl('/authors/index');
    }

    #[DataProvider('validationDataProvider')]
    public function testSubmitFormValidationRules(FunctionalTester $I, Example $example): void
    {
        $authorId = $I->grabRecord(Author::class)->getPrimaryKey();

        $I->amLoggedInAs($I->grabRecord(User::class));
        //        $I->amOnPage("/authors/$authorId/update");
        //        $I->seeResponseCodeIsSuccessful();
        //        $I->submitForm('#AuthorForm', ['AuthorForm' => $example['data']]);
        //        $I->cantSeeRecord(Author::class, ['id' => $authorId, ...$example['data']]);
        //        $I->canSeeInCurrentUrl('/authors/create');
        //
        //        foreach ($example['errors'] as $error) {
        //            $I->see($error, '.help-block');
        //        }
    }

    protected function validationDataProvider(): array
    {
        return [
            'all fields are required'        => [
                'data'   => [
                    'first_name'  => '',
                    'last_name'   => '',
                    'middle_name' => '',
                ],
                'errors' => [
                    'First name cannot be blank.',
                    'Last name cannot be blank.',
                    'Middle name cannot be blank.',
                ],
            ],
            'all fields are max 255 letters' => [
                'data'   => [
                    'first_name'  => str_repeat('a', 256),
                    'last_name'   => str_repeat('b', 256),
                    'middle_name' => str_repeat('c', 256),
                ],
                'errors' => [
                    'First name should contain at most 255 characters.',
                    'Last name should contain at most 255 characters.',
                    'Middle name should contain at most 255 characters.',
                ],
            ],
        ];
    }
}
