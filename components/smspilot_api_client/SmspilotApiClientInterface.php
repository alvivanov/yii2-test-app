<?php

namespace app\components\smspilot_api_client;

interface SmspilotApiClientInterface
{
    public function send(string $text, string $phone): void;
}
