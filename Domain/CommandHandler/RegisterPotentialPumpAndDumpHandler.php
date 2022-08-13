<?php

namespace Domain\CommandHandler;

use App\CryptocurrencyRepository;
use Domain\Command\RegisterPotentialPumpAndDump;

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
