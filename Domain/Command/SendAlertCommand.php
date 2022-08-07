<?php

namespace App\Domain\Command;

use App\Common\ValueObjects\CryptocurrencyId;

class SendAlertCommand
{
    public CryptocurrencyId $id;

    public function __construct(CryptocurrencyId $id)
    {
        $this->id = $id;
    }
}
