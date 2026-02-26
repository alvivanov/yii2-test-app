<?php

use app\components\bootstrap\EventSubscriberBootstrap;

return array_merge(['log', EventSubscriberBootstrap::class], array_keys(require(__DIR__ . '/queues.php')));
