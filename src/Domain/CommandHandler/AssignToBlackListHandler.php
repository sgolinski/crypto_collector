<?php

namespace App\Domain\CommandHandler;

use App\Domain\Command\AssignToBlackList;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class AssignToBlackListHandler
{
    private CryptocurrencyRepository $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function handle(AssignToBlackList $command): void
    {
        $this->cryptocurrencyRepository->addToBlackList($command->id);
    }
}
