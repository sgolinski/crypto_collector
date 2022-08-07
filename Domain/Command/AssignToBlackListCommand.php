<?php

namespace App\Domain\Command;

use App\Common\ValueObjects\CryptocurrencyId;

class AssignToBlackListCommand
{

    public function __construct(public CryptocurrencyId $id)
    {
    }
}
