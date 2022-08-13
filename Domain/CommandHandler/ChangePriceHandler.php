<?php

namespace Domain\CommandHandler;

use App\CryptocurrencyRepository;
use Domain\Command\ChangePrice;

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
