<?php

namespace App\Domain\Command;

use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Holders;

class AssignHoldersCommand
{
    public CryptocurrencyId $id;
    public Holders $holders;

    public function __construct(CryptocurrencyId $id, Holders $holders)
    {
        $this->id = $id;
        $this->holders = $holders;
    }
}
