<?php

namespace App\Domain\CommandHandler;

use App\Domain\Command\AssignHolders;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class AssignHoldersHandler
{
    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function handle(AssignHolders $command): void
    {
        $this->cryptocurrencyRepository->updateHolders($command->id, $command->holders);
    }
}
