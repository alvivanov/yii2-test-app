<?php

$faker = Faker\Factory::create();

return array_merge(
    array_map(
        static fn (int $i): array => [
            'id'               => $i,
            'title'            => "$i $faker->word test $i $faker->word",
            'publication_year' => 2026,
            'isbn'             => $faker->isbn13(),
            'main_page_image'  => "/uploads/$i.jpg",
            'created_at'       => $faker->date('Y-m-d H:i:s'),
            'updated_at'       => $faker->date('Y-m-d H:i:s'),
        ],
        range(1, 55)
    ),
    array_map(
        static fn (int $i): array => [
            'id'               => $i,
            'title'            => "$i $faker->word test $i $faker->word",
            'publication_year' => 2025,
            'isbn'             => $faker->isbn13(),
            'main_page_image'  => "/uploads/$i.jpg",
            'created_at'       => $faker->date('Y-m-d H:i:s'),
            'updated_at'       => $faker->date('Y-m-d H:i:s'),
        ],
        [56]
    ),
);
