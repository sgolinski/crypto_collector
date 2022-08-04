<?php

namespace App\Domain\CommandHandler;

use App\Domain\Command\AssignToBlackListCommand;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class AssignToBlackListCommandHandler
{
    private $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function handle(AssignToBlackListCommand $command): void
    {
        $this->cryptocurrencyRepository->addToBlackList($command->id);
    }
}
