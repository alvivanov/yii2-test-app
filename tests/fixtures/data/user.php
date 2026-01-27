<?php

$faker = Faker\Factory::create();

return [
    'user_1' => [
        'id'   => 1,
        'username'   => $faker->userName,
        'password'   => Yii::$app->security->generatePasswordHash('password_1'),
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
    ],
];
