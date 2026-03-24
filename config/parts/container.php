<?php

use app\components\notification_channels\SmspilotChannel;
use app\components\smspilot_api_client\SmspilotApiClient;
use app\components\smspilot_api_client\SmspilotApiClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;
use tuyakhov\notifications\Notifier;
use Yii2Extended\Yii2Log\Psr3ToYii2Logger;

return [
    'definitions' => [
        ClientInterface::class            => Client::class,
        SmspilotApiClientInterface::class => [SmspilotApiClient::class, ['apiKey' => env('SMSPILOT_API_KEY')]],
    ],
    'singletons'  => [
        LoggerInterface::class => Psr3ToYii2Logger::class,
        Notifier::class        => ['class' => Notifier::class, 'channels' => ['sms' => SmspilotChannel::class]],
    ],
];
