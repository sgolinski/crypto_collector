<?php

namespace App\Domain\CommandHandler;

use App\Domain\Command\CryptocurrencyRegisterCommand;
use App\Domain\Model\Cryptocurrency;
use App\Infrastructure\Repository\CryptocurrencyRepository;

class CryptocurrencyRegisterCommandHandler
{
    private $cryptocurrencyRepository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->cryptocurrencyRepository = $cryptocurrencyRepository;
    }

    public function handle(CryptocurrencyRegisterCommand $command): void
    {
        $cryptocurrency = Cryptocurrency::writeNewFrom(
            $command->address,
            $command->name,
            $command->price,
            $command->chain
        );

        $this->cryptocurrencyRepository->add($cryptocurrency);
    }
}
