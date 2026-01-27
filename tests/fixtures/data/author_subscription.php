<?php

$faker = Faker\Factory::create();

return [
    [
        'phone'      => '+79130025454',
        'author_id'  => 1,
        'created_at' => $faker->date('Y-m-d H:i:s'),
    ],
];
