<?php

namespace app\components\smspilot_api_client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;

final readonly class SmspilotApiClient implements SmspilotApiClientInterface
{
    public function __construct(private ClientInterface $httpClient, private string $apiKey) {}

    public function send(string $text, string $phone): void
    {
        $this->httpClient->request('POST', 'https://smspilot.ru/api.php', [
            RequestOptions::JSON => ['send' => $text, 'to' => $phone, 'apikey' => $this->apiKey],
        ]);
    }
}
