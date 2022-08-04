<?php

namespace App\Domain\CommandHandler;

use App\Domain\Command\ChangePriceCommand;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class ChangePriceCommandHandler
{
    private $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function handle(ChangePriceCommand $command): void
    {
        $this->cryptocurrencyRepository->update($command->id(), $command->price());
    }
}
