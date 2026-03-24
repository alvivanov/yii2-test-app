<?php

use app\components\bootstrap\EventSubscriberBootstrap;
use yii\queue\sync\Queue;

return [
    'id'         => 'basic-tests',
    'bootstrap'  => [EventSubscriberBootstrap::class],
    'basePath'   => dirname(__DIR__),
    'params'     => require __DIR__ . '/parts/params.php',
    'container'  => require __DIR__ . '/parts/container.php',
    'aliases'    => ['@bower' => '@vendor/bower-asset', '@npm' => '@vendor/npm-asset'],
    'language'   => 'en-US',
    'components' => array_merge(
        require(__DIR__ . '/parts/components.php'),
        array_map(static fn(): string => Queue::class, require(__DIR__ . '/parts/queues.php')),
        [
            'assetManager' => ['basePath' => __DIR__ . '/../web/assets'],
            'urlManager'   => array_merge(require __DIR__ . '/parts/routes.php', ['showScriptName' => true]),
            'request'      => ['cookieValidationKey' => 'test', 'enableCsrfValidation' => false],
            'errorHandler' => ['errorAction' => 'site/error'],
        ],
    ),
];
