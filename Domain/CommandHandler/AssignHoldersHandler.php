<?php

namespace Domain\CommandHandler;

use App\CryptocurrencyRepository;
use Domain\Command\AssignHolders;

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
