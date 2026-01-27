<?php

namespace app\components\container;

use yii\di\Instance as BaseInstance;
use yii\helpers\ArrayHelper;

final class Instance extends BaseInstance
{
    /**
     * @inheritDoc
     */
    public function get($container = null): mixed
    {
        if (str_starts_with($this->id, 'config.')) {
            return ArrayHelper::getValue(\Yii::$app->params, substr($this->id, strlen('config.')));
        }

        return parent::get($container);
    }
}
