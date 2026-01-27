<?php

namespace app\commands;

use app\models\User;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

final class UsersController extends Controller
{
    public function actionCreate(string $username, string $password): void
    {
        Console::stdout("Creating user with username '$username'... ");
        $user = new User(['username' => $username, 'password' => Yii::$app->security->generatePasswordHash($password)]);

        if (!$user->save()) {
            Console::output('Error!');
            Console::error(implode(PHP_EOL, $user->getErrorSummary(true)));

            return;
        }

        Console::output('Success');
    }
}
