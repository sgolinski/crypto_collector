<?php

namespace App\Infrastructure\Projection;

use App\Domain\Event\PriceWasChangedEventHandler;
use App\Repository\CryptocurrencyRepository;

class CryptocurrencyPriceWasChangedProjection
{
    private $repository;

    public function __construct(CryptocurrencyRepository $cryptocurrencyRepository)
    {
        $this->repository = $cryptocurrencyRepository;
    }

    public function listensTo()
    {
        return PriceWasChangedEventHandler::class;
    }


    protected function applyCryptocurrencyPriceWasChanged()
    {
    }

    public function project($event)
    {
        $this->client->index([
            'index' => 'cryptocurrencies',
            'type' => 'cryptocurrency',
            'id' => $event->getCryptocurrencyId(),
            'body' => [
                'content' => $event->getName(),
            ]
        ]);
    }
}
