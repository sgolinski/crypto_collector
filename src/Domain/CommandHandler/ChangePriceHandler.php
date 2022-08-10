<?php

namespace App\Domain\CommandHandler;

use App\Domain\Command\ChangePrice;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class ChangePriceHandler
{
    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function handle(ChangePrice $command): void
    {
        $this->cryptocurrencyRepository->update($command->id(), $command->price());
    }
}
