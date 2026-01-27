<?php

namespace app\components\bootstrap;

use app\components\event_handlers\EventHandlerInterface;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\InvalidConfigException;

final readonly class EventSubscriberBootstrap implements BootstrapInterface
{
    /**
     * @inheritDoc
     *
     * @throws InvalidConfigException
     */
    public function bootstrap($app): void
    {
        foreach (require Yii::getAlias('@app/config/events.php') as $eventSenderClass => $eventHandlerMap) {
            foreach ($eventHandlerMap as $event => $handlerClasses) {
                foreach ($handlerClasses as $handlerClass) {
                    if (!is_a($handlerClass, EventHandlerInterface::class, true)) {
                        throw new InvalidConfigException(
                            sprintf(
                                'Event handler class %s must implement %s',
                                $handlerClass,
                                EventHandlerInterface::class
                            )
                        );
                    }

                    Event::on($eventSenderClass, $event, [Yii::createObject($handlerClass), 'handle']);
                }
            }
        }
    }
}
