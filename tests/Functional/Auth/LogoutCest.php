<?php

declare(strict_types=1);

namespace app\tests\Functional\Auth;

use app\tests\fixtures\UsersFixture;
use Codeception\Attribute\Group;
use FunctionalTester;
use Yii;

#[Group('functional.auth')]
final readonly class LogoutCest
{
    public function _fixtures(): array
    {
        return ['users' => UsersFixture::class];
    }

    public function testPageAuth(FunctionalTester $I): void
    {
        $I->amOmPagePost('/auth/logout');
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/auth/login');
    }

    public function testSuccess(FunctionalTester $I): void
    {
        $userFixture = $I->grabFixture('users', 'user_1');

        $I->amLoggedInAs($userFixture['id']);
        $I->amOmPagePost('/auth/logout');
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/index-test.php');
        $I->assertNotEquals($userFixture['id'], Yii::$app->user->id);
    }
}
