<?php

namespace App\Application\Service;

use App\Domain\Entity\Token;
use App\Domain\Query\FindAllCompleteCryptocurrencyTransactions;
use App\Domain\QueryHandler\FindAllCompleteCryptocurrencyTransactionsHandler;
use App\Infrastructure\Repository\PDOCryptocurrencyRepository;

class SendAlerts
{
    protected NotificationService $service;

    public function __construct(PDOCryptocurrencyRepository $cryptocurrencyRepository, NotificationService $service)
    {

        $this->service = $service;
    }

    public function invoke(): void
    {
        $this->sendAlertsForCompletedTokens();
    }

    public function sendAlertsForCompletedTokens(): void
    {
        $allCryptocurrenciesCompleteQuery = new FindAllCompleteCryptocurrencyTransactions();
        $allCryptocurrenciesCompleteQueryHandler = new FindAllCompleteCryptocurrencyTransactionsHandler($this->cryptocurrencyRepository);
        $completedTokensNotSended = $allCryptocurrenciesCompleteQueryHandler->__invoke($allCryptocurrenciesCompleteQuery);

        foreach ($completedTokensNotSended as $completeToken) {
            $this->service->sendMessage($this->createAlertMessage($completeToken));
            $sendAlertCommand = new SendAlertCommand($completeToken->id());
            $sendAlertCommandHandler = new SendAlertCommandHandler($this->cryptocurrencyRepository);
            $sendAlertCommandHandler->handle($sendAlertCommand);
        }
    }

    private function createAlertMessage(Token $completeToken)
    {
        return 'Name: ' . $completeToken->name() . PHP_EOL .
            'Drop price: ' . $completeToken->price() . ' ' . $completeToken->chain() . PHP_EOL .
            'Coingecko: https://www.coingecko.com/en/coins/' . $completeToken->address() . PHP_EOL .
            'Poocoin: https://poocoin.app/tokens/' . $completeToken->address() . PHP_EOL;
    }
}
