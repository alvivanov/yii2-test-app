<?php

namespace app\components\event_handlers;

use app\models\Book;
use app\notifications\NewBookAdded;
use tuyakhov\notifications\Notifier;
use yii\base\Event;
use yii\base\InvalidConfigException;

final readonly class SendNewBookNotificationToSubscribers implements EventHandlerInterface
{
    public function __construct(private Notifier $notifier)
    {
    }

    /**
     * @throws InvalidConfigException
     */
    public function handle(Event $event): void
    {
        $newBook = $event->sender;

        if ($newBook instanceof Book) {
            $newBook->loadRelations('authors.subscriptions');

            foreach ($newBook->authors as $author) {
                if (count($author->subscriptions) > 0) {
                    $this->notifier->send($author->subscriptions, new NewBookAdded($newBook, $author));
                }
            }
        }
    }
}
