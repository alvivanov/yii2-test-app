<?php

use yii\db\Connection;

return [
    'class' => Connection::class,
    'dsn' => sprintf('mysql:host=%s;dbname=%s', env('DB_HOST'), env('DB_DATABASE')),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
