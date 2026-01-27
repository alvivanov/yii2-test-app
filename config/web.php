<?php

use app\components\bootstrap\EventSubscriberBootstrap;
use app\components\FlySystem\LocalComponent;
use app\models\User;

$config = [
    'id'         => 'basic',
    'basePath'   => dirname(__DIR__),
    'homeUrl'    => ['/'],
    'bootstrap'  => ['log', EventSubscriberBootstrap::class],
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'fs'           => [
            'class'  => LocalComponent::class,
            'path'   => '@app/web/uploads',
            'action' => '/uploads',
        ],
        'i18n'         => [
            'translations' => [
                'app*' => [
                    'class'   => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'app'          => 'app.php',
                        'app/error'    => 'error.php',
                        'app/nav-menu' => 'error.php',
                    ],
                ],
            ],
        ],
        'request'      => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'fq7truhlJDvZXM2MyeMwgtM1w9TDkEYg',
        ],
        'cache'        => [
            'class' => 'yii\caching\FileCache',
        ],
        'user'         => [
            'identityClass' => User::class,
            'loginUrl'      => '/auth/login',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db'           => require __DIR__ . '/db.php',
        'urlManager'   => require __DIR__ . '/routes.php',
    ],
    'params'     => require __DIR__ . '/params.php',
    'container'  => require(__DIR__ . '/di.php'),
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][]      = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][]    = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
