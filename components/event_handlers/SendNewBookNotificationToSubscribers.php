<?php

namespace app\components\event_handlers;

use app\jobs\SendNotification;
use app\models\Book;
use app\notifications\NewBookAdded;
use Yii;
use yii\base\Event;

final readonly class SendNewBookNotificationToSubscribers implements EventHandlerInterface
{
    public function handle(Event $event): void
    {
        $newBook = $event->sender;

        if ($newBook instanceof Book) {
            $newBook->loadRelations('authors.subscriptions');

            foreach ($newBook->authors as $author) {
                if (count($author->subscriptions) > 0) {
                    Yii::$app->notificationQueue->push(
                        Yii::createObject(SendNotification::class, [
                            'notifiers'    => $author->subscriptions,
                            'notification' => new NewBookAdded($newBook, $author),
                        ])
                    );
                }
            }
        }
    }
}
