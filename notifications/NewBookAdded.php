<?php

namespace app\notifications;

use app\models\Author;
use app\models\Book;
use tuyakhov\notifications\messages\SmsMessage;
use tuyakhov\notifications\NotificationInterface;
use tuyakhov\notifications\NotificationTrait;
use Yii;

final readonly class NewBookAdded implements NotificationInterface
{
    use NotificationTrait;

    public function __construct(private Book $book, private Author $author) {}

    public function exportForSms(): SmsMessage
    {
        return new SmsMessage([
            'body' => Yii::t(
                'app/notifications',
                '{author} published new book "{book}"',
                ['author' => $this->author->fullName, 'book' => $this->book->title]
            ),
        ]);
    }
}
