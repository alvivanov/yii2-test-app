<?php

namespace Functional\Authors;

use app\models\Author;
use app\models\User;
use app\tests\fixtures\AuthorsFixture;
use app\tests\fixtures\UsersFixture;
use Codeception\Attribute\Group;
use FunctionalTester;

#[Group('functional.authors')]
final readonly class DeleteAuthorCest
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

        $I->amOmPagePost("/authors/$authorId/delete");
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/auth/login');
    }

    public function testPageNotFound(FunctionalTester $I): void
    {
        $I->amLoggedInAs($I->grabRecord(User::class));
        $I->amOmPagePost('/authors/123123123/delete');
        $I->seePageNotFound();
    }

    public function testSuccess(FunctionalTester $I): void
    {
        $authorId = $I->grabRecord(Author::class)->getPrimaryKey();

        $I->amLoggedInAs($I->grabRecord(User::class));
        $I->amOmPagePost("/authors/$authorId/delete");
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/authors');
        $I->dontSeeRecord(Author::class, ['id' => $authorId]);
    }
}
