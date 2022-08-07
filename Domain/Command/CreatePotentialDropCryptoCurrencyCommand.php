<?php

namespace App\Domain\Command;

use App\Domain\Entity\Cryptocurrency;


class CreatePotentialDropCryptoCurrencyCommand
{
    public Cryptocurrency $token;

    public function __construct(Cryptocurrency $token)
    {
        $this->token = $token;
    }

    public function token(): Cryptocurrency
    {
        return $this->token;
    }
}