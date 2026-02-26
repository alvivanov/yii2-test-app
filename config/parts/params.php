<?php

return [
    'adminEmail'   => 'admin@example.com',
    'senderEmail'  => 'noreply@example.com',
    'senderName'   => 'Example.com mailer',
    'integrations' => [
        'smspilot' => [
            'apiKey' => env('SMSPILOT_API_KEY'),
        ],
    ],
];
