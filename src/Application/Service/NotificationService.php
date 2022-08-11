<?php

namespace App\Application\Service;

use Maknz\Slack\Client as SlackClient;
use Maknz\Slack\Message;

class NotificationService
{
    private SlackClient $slack;

    private const HOOK = 'https://hooks.slack.com/services/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';

    public function __construct()
    {
        $this->slack = new SlackClient(self::HOOK);
    }

    public function sendMessage(
        string $text
    ): void
    {
        $message = new Message();
        $message->setText($text);
        $this->slack->sendMessage($message);
    }

    private function template($transaction): string
    {
        return 'Name: ' . $transaction->name()->asString() . PHP_EOL .
            'Drop price: ' . $transaction->price()->asFloat() . ' ' . $transaction->chain()->asString(). PHP_EOL .
            'Coingecko: https://www.coingecko.com/en/coins/' . $transaction->id()->asString() . PHP_EOL .
            'Poocoin: https://poocoin.app/tokens/' .$transaction->id()->asString() . PHP_EOL .
            'Tokensniffer: https://tokensniffer.com/token/' .$transaction->id()->asString(). PHP_EOL;
    }
}
