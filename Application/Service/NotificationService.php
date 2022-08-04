<?php

namespace App\Application\Service;

use App\Factory;
use Maknz\Slack\Client as SlackClient;

class NotificationService
{
    private SlackClient $slack;

    private const HOOK = 'https://hooks.slack.com/services/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';

    public function __construct()
    {
        $this->slack = Factory::createSlackClient(self::HOOK);
    }

    public function sendMessage(
        string $message
    ): void {
        $message = Factory::createSlackMessage()->setText($message);
        $this->slack->sendMessage($message);
    }
}
