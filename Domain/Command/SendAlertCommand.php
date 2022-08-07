<?php

namespace App\Domain\Command;

use App\Common\ValueObjects\CryptocurrencyId;

class SendAlertCommand
{
    public function __construct(public CryptocurrencyId $id)
    {
    }
}
