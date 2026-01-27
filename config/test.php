<?php

use app\components\bootstrap\EventSubscriberBootstrap;
use app\components\FlySystem\LocalComponent;
use app\models\User;

$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/db.php';

/**
 * Application configuration shared by all test types
 */
return [
    'id'         => 'basic-tests',
    'bootstrap'  => [EventSubscriberBootstrap::class],
    'basePath'   => dirname(__DIR__),
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language'   => 'en-US',
    'components' => [
        'db'           => $db,
        'cache'        => [
            'class' => 'yii\caching\FileCache',
        ],
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
        'mailer'       => [
            'class'            => \yii\symfonymailer\Mailer::class,
            'viewPath'         => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
            'messageClass'     => 'yii\symfonymailer\Message',
        ],
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager'   => array_merge(require __DIR__ . '/routes.php', ['showScriptName' => true]),
        'user'         => [
            'identityClass' => User::class,
            'loginUrl'      => '/auth/login',
        ],
        'request'      => [
            'cookieValidationKey'  => 'test',
            'enableCsrfValidation' => false,
            // but if you absolutely need it set cookie domain to localhost
            /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
        ],
    ],
    'params'     => $params,
    'container'  => require(__DIR__ . '/di.php'),
];
