<?php

use app\components\container\Instance;
use app\components\notification_channels\SmspilotChannel;
use app\components\smspilot_api_client\SmspilotApiClient;
use app\components\smspilot_api_client\SmspilotApiClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use tuyakhov\notifications\Notifier;
use yii\web\User;

return [
    'definitions' => [
        ClientInterface::class            => Client::class,
        SmspilotApiClientInterface::class => [
            SmspilotApiClient::class,
            ['apiKey' => Instance::of('config.integrations.smspilot.apiKey')],
        ],
    ],
    'singletons'  => [
        User::class     => Instance::of(User::class),
        Notifier::class => [
            'class'    => Notifier::class,
            'channels' => [
                'sms' => SmspilotChannel::class,
            ],
        ],
    ],
];
