<?php

declare(strict_types=1);

namespace app\tests\Unit\components;

use app\components\smspilot_api_client\SmspilotApiClient;
use Codeception\Test\Unit;
use GuzzleHttp\ClientInterface;

final class SmspilotApiClientTest extends Unit
{
    public function testSend(): void
    {
        $httpClient = $this->getMockBuilder(ClientInterface::class)->getMock();
        $httpClient->method('request')->with('POST', 'https://smspilot.ru/api.php', [
            'json' => ['send' => 'test_text', 'to' => '89121231212', 'apikey' => 'test_api_key'],
        ]);

        new SmspilotApiClient($httpClient, 'test_api_key')->send('test_text', '89121231212');
    }
}
