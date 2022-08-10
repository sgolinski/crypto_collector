<?php

namespace App\Domain\Command;

use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Holders;
use App\Common\ValueObjects\TransactionId;

class AssignHolders
{
    public TransactionId $id;
    public Holders $holders;

    public function __construct(TransactionId $id, Holders $holders)
    {
        $this->id = $id;
        $this->holders = $holders;
    }
}
