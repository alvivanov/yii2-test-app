<?php

namespace app\components\exceptions;

use yii\web\HttpException;

final class ModelNotFoundException extends HttpException
{
    public function __construct(?string $message = null, $previous = null)
    {
        parent::__construct(404, $message, previous: $previous);
    }
}
