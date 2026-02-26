<?php

$faker = Faker\Factory::create();
$result = [];

$getAuthorIds = static fn (int $bookId): array => match (true) {
    $bookId <= 1 => [1, 2],
    $bookId <= 3 => [3],
    $bookId <= 6 => [4],
    $bookId <= 10 => [5],
    $bookId <= 15 => [6],
    $bookId <= 21 => [7],
    $bookId <= 28 => [8],
    $bookId <= 36 => [9],
    $bookId <= 45 => [10],
    $bookId > 45 => [11],
};

foreach (range(1, 56) as $bookId) {
    foreach ($getAuthorIds($bookId) as $authorId) {
        $result[] = [
            'book_id'    => $bookId,
            'author_id'  => $authorId,
            'created_at' => $faker->date('Y-m-d H:i:s'),
        ];
    }
}

return $result;
