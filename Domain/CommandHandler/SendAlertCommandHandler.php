<?php

namespace App\Domain\CommandHandler;

use App\Domain\Command\SendAlertCommand;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class SendAlertCommandHandler
{
    private $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function handle(SendAlertCommand $command): void
    {
        $this->cryptocurrencyRepository->updateAlert($command->id);
    }
}
