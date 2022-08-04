<?php

namespace App\Infrastructure\Projection;

use App\Domain\Event\CryptocurrencyPriceWasChanged;
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
        return CryptocurrencyPriceWasChanged::class;
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
