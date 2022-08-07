<?php

namespace App\Domain\CommandHandler;

use App\Domain\Command\CreatePotentialDropCryptoCurrencyCommand;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class CreatePotentialDropCryptoCurrencyCommandHandler
{
    private $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function handle(CreatePotentialDropCryptoCurrencyCommand $command): void
    {
        $this->cryptocurrencyRepository->add($command->token());
    }
}