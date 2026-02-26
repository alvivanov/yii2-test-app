<?php

namespace app\components\notification_channels;

use app\components\smspilot_api_client\SmspilotApiClientInterface;
use tuyakhov\notifications\channels\ChannelInterface;
use tuyakhov\notifications\NotifiableInterface;
use tuyakhov\notifications\NotificationInterface;
use yii\base\InvalidArgumentException;

final readonly class SmspilotChannel implements ChannelInterface
{
    public function __construct(private SmspilotApiClientInterface $apiClient) {}

    /**
     * @inheritdoc
     */
    public function send(NotifiableInterface $recipient, NotificationInterface $notification): void
    {
        if (!$phone = $recipient->routeNotificationFor('sms')) {
            throw new InvalidArgumentException('No phone provided');
        }

        $this->apiClient->send($notification->exportFor('sms')->body, $phone);
    }
}
