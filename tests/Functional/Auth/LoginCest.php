<?php

declare(strict_types=1);

namespace app\tests\Functional\Auth;

use app\tests\fixtures\UsersFixture;
use Codeception\Attribute\DataProvider;
use Codeception\Attribute\Group;
use Codeception\Example;
use FunctionalTester;
use Yii;

#[Group('functional.auth')]
final readonly class LoginCest
{
    public function _fixtures(): array
    {
        return ['users' => UsersFixture::class];
    }

    public function testSuccess(FunctionalTester $I): void
    {
        $userFixture = $I->grabFixture('users', 'user_1');

        $I->amOnPage('/auth/login');
        $I->submitForm('#login-form', ['LoginForm' => ['username' => $userFixture['username'], 'password' => 'password_1']]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/index-test.php');
        $I->assertEquals($userFixture['id'], Yii::$app->user->id);
    }

    public function testRedirectIfAlreadyLoggedIn(FunctionalTester $I): void
    {
        $userFixture = $I->grabFixture('users', 'user_1');

        $I->amLoggedInAs($userFixture['id']);
        $I->amOnPage('/auth/login');
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/index-test.php');
        $I->assertEquals($userFixture['id'], Yii::$app->user->id);
    }

    #[DataProvider('validationDataProvider')]
    public function testValidationRules(FunctionalTester $I, Example $example): void
    {
        $I->amOnPage('/auth/login');
        $I->seeResponseCodeIsSuccessful();
        $I->submitForm('#login-form', ['LoginForm' => $example['data']]);
        $I->canSeeInCurrentUrl('/auth/login');

        foreach ($example['errors'] as $error) {
            $I->see($error);
        }
    }

    protected function validationDataProvider(): array
    {
        return [
            'all fields are required'             => [
                'data'   => [
                    'username' => '',
                    'password' => '',
                ],
                'errors' => [
                    'Username cannot be blank.',
                    'Password cannot be blank.',
                ],
            ],
            'all fields are min 6 letters long'   => [
                'data'   => [
                    'username' => str_repeat('a', 5),
                    'password' => str_repeat('a', 5),
                ],
                'errors' => [
                    'Username should contain at least 6 characters.',
                    'Password should contain at least 6 characters.',
                ],
            ],
            'all fields are max 255 letters long' => [
                'data'   => [
                    'username' => str_repeat('a', 256),
                    'password' => str_repeat('a', 256),
                ],
                'errors' => [
                    'Username should contain at most 255 characters.',
                    'Password should contain at most 255 characters.',
                ],
            ],
            'invalid credentials'                 => [
                'data'   => [
                    'username' => 'invalid_username',
                    'password' => 'invalid_username',
                ],
                'errors' => [
                    'Incorrect username or password.',
                ],
            ],
        ];
    }
}
