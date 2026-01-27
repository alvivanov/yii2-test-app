<?php

declare(strict_types=1);

namespace app\tests\Unit\components;

use app\components\notification_channels\SmspilotChannel;
use app\components\smspilot_api_client\SmspilotApiClientInterface;
use Codeception\Test\Unit;
use tuyakhov\notifications\messages\AbstractMessage;
use tuyakhov\notifications\NotifiableInterface;
use tuyakhov\notifications\NotificationInterface;

final class SmspilotChannelTest extends Unit
{
    public function testSend(): void
    {
        $httpClient = $this->getMockBuilder(SmspilotApiClientInterface::class)->getMock();
        $httpClient->method('send')->with('test_text', '89121231212');

        $message       = $this->getMockBuilder(AbstractMessage::class)->getMock();
        $message->body = 'test_text';
        $notification  = $this->getMockBuilder(NotificationInterface::class)->getMock();
        $notification->method('exportFor')->with('sms')->willReturn($message);

        $notifiable = $this->getMockBuilder(NotifiableInterface::class)->getMock();
        $notifiable->method('routeNotificationFor')->with('sms')->willReturn('89121231212');

        new SmspilotChannel($httpClient)->send($notifiable, $notification);
    }
}
