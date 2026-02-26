<?php

return [
    'id'                  => 'basic-console',
    'basePath'            => dirname(__DIR__),
    'controllerNamespace' => 'app\commands',
    'bootstrap'           => require(__DIR__ . '/parts/bootstrap.php'),
    'params'              => require __DIR__ . '/parts/params.php',
    'container'           => require(__DIR__ . '/parts/container.php'),
    'components'          => require(__DIR__ . '/parts/components.php'),
];
