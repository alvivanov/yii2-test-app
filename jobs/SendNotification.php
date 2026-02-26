<?php

namespace app\jobs;

use tuyakhov\notifications\NotificationInterface;
use tuyakhov\notifications\Notifier;
use yii\queue\JobInterface;

final readonly class SendNotification implements JobInterface
{
    public function __construct(
        private Notifier $notifier,
        private array $notifiers,
        private NotificationInterface $notification
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute($queue): void
    {
        $this->notifier->send($this->notifiers, $this->notification);
    }
}
