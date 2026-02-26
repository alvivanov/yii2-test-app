<?php

use app\components\event_handlers\SendNewBookNotificationToSubscribers;
use app\models\Book;

return [
    Book::class => [
        Book::BOOK_ADDED_EVENT => [SendNewBookNotificationToSubscribers::class],
    ],
];
