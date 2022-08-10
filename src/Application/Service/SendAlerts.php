<?php

namespace App\Application\Service;


class SendAlerts
{
    private function createAlertMessage(Token $completeToken)
    {
        return 'Name: ' . $completeToken->name() . PHP_EOL .
            'Drop price: ' . $completeToken->price() . ' ' . $completeToken->chain() . PHP_EOL .
            'Coingecko: https://www.coingecko.com/en/coins/' . $completeToken->address() . PHP_EOL .
            'Poocoin: https://poocoin.app/tokens/' . $completeToken->address() . PHP_EOL;
    }
}
