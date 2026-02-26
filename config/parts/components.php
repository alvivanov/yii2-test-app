<?php

use app\components\FlySystem\LocalComponent;
use yii\caching\FileCache;
use yii\i18n\PhpMessageSource;
use yii\log\FileTarget;
use yii\redis\Connection;

return array_merge(require(__DIR__ . '/queues.php'), [
    'db'    => require __DIR__ . '/db.php',
    'redis' => ['class' => Connection::class, 'hostname' => env('REDIS_HOST')],
    'cache' => FileCache::class,
    'fs'    => ['class' => LocalComponent::class, 'path' => '@app/web/uploads', 'action' => '/uploads'],
    'i18n'  => [
        'translations' => [
            'app*' => [
                'class'   => PhpMessageSource::class,
                'fileMap' => [
                    'app'          => 'app.php',
                    'app/error'    => 'error.php',
                    'app/nav-menu' => 'error.php',
                ],
            ],
        ],
    ],
    'log'   => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets'    => [
            ['class' => FileTarget::class, 'levels' => ['error', 'warning']],
        ],
    ],
]);
