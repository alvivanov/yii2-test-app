<?php

use yii\helpers\ArrayHelper;
use yii\queue\redis\Queue;
use yii\queue\serializers\IgbinarySerializer;

$queues = ['notification'];

return ArrayHelper::map(
    $queues,
    /** @see __Application Добавить в phpDoc компонент очереди "@property-read \yii\queue\Queue {$queue}Queue" для автокомплита */
    static fn(string $queue): string => "{$queue}Queue",
    static fn(string $queue): array => [
        'class'      => Queue::class,
        'channel'    => $queue,
        'serializer' => IgbinarySerializer::class,
    ],
);
