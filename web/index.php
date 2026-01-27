<?php

define('DOTENV_PATH', './..');
define('DOTENV_FILE', '.env');
define('DOTENV_OVERLOAD', false);

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
Yii::$classMap = array_merge(Yii::$classMap, require __DIR__ . '/../config/autoload.php');

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
