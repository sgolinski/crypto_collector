<?php

namespace App\Domain\CommandHandler;

use App\Domain\Command\RegisterTransaction;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class RegisterTransactionHandler
{
    private $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }
    public function handle(RegisterTransaction $command): void
    {
        $this->cryptocurrencyRepository->save($command->transaction());
    }
}
