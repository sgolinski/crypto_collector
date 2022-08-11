<?php

namespace App\Domain\CommandHandler;

use App\Domain\Command\RegisterPotentialPumpAndDump;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class RegisterPotentialPumpAndDumpHandler
{
    private $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function handle(RegisterPotentialPumpAndDump $command): void
    {
        $this->cryptocurrencyRepository->addPotentialPumpAndDump($command->transaction());
    }
}
