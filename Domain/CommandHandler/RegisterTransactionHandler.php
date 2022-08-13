<?php

namespace Domain\CommandHandler;

use App\CryptocurrencyRepository;
use Domain\Command\RegisterTransaction;

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
