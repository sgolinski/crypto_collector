<?php

namespace App\Infrastructure\Projection;

class CryptocurrencyWasRegisteredProjection
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function listensTo()
    {
        return CryptocurrencyWasRegistered::class;
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
