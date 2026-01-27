<?php

use yii\helpers\ArrayHelper;

return ArrayHelper::map(
    array_filter(
        scandir(__DIR__ . '/../components/container'),
        static fn(string $file): bool => str_ends_with($file, '.php')
    ),
    static fn(string $file): string => basename("app\components\container\\$file", '.php'),
    static fn(string $file): string => __DIR__ . "/../components/container/$file",
);
