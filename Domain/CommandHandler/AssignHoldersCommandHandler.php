<?php

namespace App\Domain\CommandHandler;

use App\Domain\Command\AssignHoldersCommand;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class AssignHoldersCommandHandler
{
    private $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function handle(AssignHoldersCommand $command): void
    {
        $this->cryptocurrencyRepository->updateHolders($command->id, $command->holders);
    }
}
