<?php

$config = [
    'id'         => 'basic',
    'basePath'   => dirname(__DIR__),
    'homeUrl'    => ['/'],
    'bootstrap'  => require(__DIR__ . '/parts/bootstrap.php'),
    'params'     => require __DIR__ . '/parts/params.php',
    'container'  => require(__DIR__ . '/parts/container.php'),
    'aliases'    => ['@bower' => '@vendor/bower-asset', '@npm' => '@vendor/npm-asset'],
    'components' => array_merge_recursive(require(__DIR__ . '/parts/components.php'), [
        'urlManager'   => require __DIR__ . '/parts/routes.php',
        'request'      => ['cookieValidationKey' => 'fq7truhlJDvZXM2MyeMwgtM1w9TDkEYg'],
        'errorHandler' => ['errorAction' => 'site/error'],
    ]),
];

if (YII_ENV_DEV) {
    $config['bootstrap'][]    = 'gii';
    $config['modules']['gii'] = [
        'class'      => yii\gii\Module::class,
        'allowedIPs' => [env('SUBNET')],
        'generators' => ['job' => \yii\queue\gii\Generator::class],
    ];

    $config['bootstrap'][]      = 'debug';
    $config['modules']['debug'] = [
        'class'             => yii\debug\Module::class,
        'allowedIPs'        => [env('SUBNET')],
        'panels'            => ['queue' => yii\queue\debug\Panel::class],
        'traceLine'         => env('DEBUG_TRACE_LINE', '<a target="_blank" href="http://localhost:63342/api/file{file}:{line}">{text}</a>'),
        'tracePathMappings' => ['/app' => '/'],
    ];
}

return $config;
