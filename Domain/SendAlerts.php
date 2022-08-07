<?php

namespace App\Domain;

use App\Application\Service\CrawlerDexTracker;
use App\Application\Service\NotificationService;
use App\Domain\Command\SendAlertCommand;
use App\Domain\CommandHandler\SendAlertCommandHandler;
use App\Domain\Entity\Token;
use App\Domain\Query\AllCryptocurrenciesCompleteQuery;
use App\Domain\QueryHandler\AllCryptocurrenciesCompleteQueryHandler;
use App\Infrastructure\Repository\PDOCryptocurrencyRepository;

class SendAlerts extends CrawlerDexTracker implements Crawler
{
    protected NotificationService $service;

    public function __construct(PDOCryptocurrencyRepository $cryptocurrencyRepository, NotificationService $service)
    {
        parent::__construct($cryptocurrencyRepository);
        $this->service = $service;
    }

    public function invoke(): void
    {
        $this->sendAlertsForCompletedTokens();
    }

    public function sendAlertsForCompletedTokens(): void
    {
        $allCryptocurrenciesCompleteQuery = new AllCryptocurrenciesCompleteQuery();
        $allCryptocurrenciesCompleteQueryHandler = new AllCryptocurrenciesCompleteQueryHandler($this->cryptocurrencyRepository);
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
