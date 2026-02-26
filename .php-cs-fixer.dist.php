<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return new Config()
    ->setRiskyAllowed(false)
    ->setRules(['@auto' => true, '@PSR12' => true, '@PHP8x5Migration' => true])
    ->setFinder(new Finder()->in(__DIR__)->exclude(['runtime', 'web']));
