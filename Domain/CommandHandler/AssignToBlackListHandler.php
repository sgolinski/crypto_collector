<?php

namespace Domain\CommandHandler;

use App\CryptocurrencyRepository;
use Domain\Command\AssignToBlackList;

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
