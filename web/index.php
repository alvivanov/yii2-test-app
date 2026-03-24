<?php

define('DOTENV_PATH', './..');
define('DOTENV_FILE', '.env');
define('DOTENV_OVERLOAD', false);

require __DIR__ . '/../vendor/autoload.php';

defined('YII_DEBUG') or define('YII_DEBUG', env('APP_DEBUG'));
defined('YII_ENV') or define('YII_ENV', env('APP_ENV', 'prod'));

require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

new yii\web\Application($config)->run();
