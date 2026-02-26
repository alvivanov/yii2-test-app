<?php

use yii\helpers\ArrayHelper;

$faker = Faker\Factory::create();

return array_merge(
    [
        'author_with_subscription' => [
            'id'          => 1,
            'first_name'  => $faker->firstName,
            'last_name'   => "1 $faker->lastName",
            'middle_name' => $faker->lastName,
            'created_at'  => $faker->date('Y-m-d H:i:s'),
            'updated_at'  => $faker->date('Y-m-d H:i:s'),
        ],
    ],
    ArrayHelper::map(
        range(2, 20),
        static fn (int $i): string => "author_without_subscription_$i",
        static fn (int $i): array => [
            'id'          => $i,
            'first_name'  => $faker->firstName,
            'last_name'   => "$i $faker->lastName",
            'middle_name' => $faker->lastName,
            'created_at'  => $faker->date('Y-m-d H:i:s'),
            'updated_at'  => $faker->date('Y-m-d H:i:s'),
        ]
    )
);
