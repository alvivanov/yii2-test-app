<?php

namespace Functional\Authors;

use app\models\Author;
use app\models\AuthorSubscription;
use app\tests\fixtures\AuthorsFixture;
use app\tests\fixtures\AuthorSubscriptionsFixture;
use Codeception\Attribute\DataProvider;
use Codeception\Attribute\Group;
use Codeception\Example;
use FunctionalTester;

#[Group('functional.authors')]
final readonly class SubscribeForNewBooksCest
{
    public function _fixtures(): array
    {
        return [
            'author_subscriptions' => AuthorSubscriptionsFixture::class,
            'authors'              => AuthorsFixture::class,
        ];
    }

    public function testPageNotFound(FunctionalTester $I): void
    {
        $I->amOmPagePost('/authors/123123123/subscribe-for-new-books');
        $I->seePageNotFound();
    }

    #[DataProvider('validationDataProvider')]
    public function testValidationRules(FunctionalTester $I, Example $example): void
    {
        $authorId = $I->grabFixture('authors', $example['fixture_index'])['id'];
        $phone    = $example['phone'];

        $I->amOnPage("/authors/$authorId/subscribe-for-new-books");
        $I->seeResponseCodeIsSuccessful();
        $I->submitForm('#subscribe-form', ['NewBookSubscriptionForm' => ['phone' => $phone]]);
        $I->canSeeInCurrentUrl("/authors/$authorId/subscribe-for-new-books");
        $I->see($example['error']);

        if ($example['fixture_index'] !== 'author_with_subscription') {
            $I->cantSeeRecord(AuthorSubscription::class, ['author_id' => $authorId, 'phone' => $phone]);
        }
    }

    public function testSuccess(FunctionalTester $I): void
    {
        $authorId = $I->grabRecord(Author::class)->getPrimaryKey();
        $phone    = '+79130024343';

        $I->amOnPage("/authors/$authorId/subscribe-for-new-books");
        $I->seeResponseCodeIsSuccessful();
        $I->submitForm('#subscribe-form', ['NewBookSubscriptionForm' => ['phone' => $phone]]);
        $I->seeCurrentUrlEquals('/authors');
        $I->canSeeRecord(AuthorSubscription::class, ['author_id' => $authorId, 'phone' => $phone]);
    }

    protected function validationDataProvider(): array
    {
        return [
            'subscription is already existing' => [
                'fixture_index' => 'author_with_subscription',
                /** @see /tests/fixtures/data/author_subscription.php */
                'phone'         => '+79130025454',
                'error'         => 'This phone number has already been taken.',
            ],
            'invalid phone format'             => [
                'fixture_index' => 'author_without_subscription_2',
                'phone'         => '89130025453-112',
                'error'         => 'The format of Phone is invalid',
            ],
        ];
    }
}
