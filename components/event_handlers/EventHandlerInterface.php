<?php

namespace app\components\event_handlers;

use yii\base\Event;

interface EventHandlerInterface
{
    public function handle(Event $event): void;
}
