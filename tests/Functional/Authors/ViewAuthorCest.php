<?php

namespace Functional\Authors;

use app\models\Author;
use app\tests\fixtures\AuthorsFixture;
use Codeception\Attribute\Group;
use FunctionalTester;

#[Group('functional.authors')]
final readonly class ViewAuthorCest
{
    public function _fixtures(): array
    {
        return ['authors' => AuthorsFixture::class];
    }

    public function testPageNotFound(FunctionalTester $I): void
    {
        $I->amOnPage('/authors/123123123');
        $I->seePageNotFound();
    }

    public function testSuccess(FunctionalTester $I): void
    {
        $authorId = $I->grabRecord(Author::class)->getPrimaryKey();

        $I->amOnPage("/authors/$authorId");
        $I->seeResponseCodeIsSuccessful();
    }
}
