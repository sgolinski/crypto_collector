<?php

namespace Domain\Command;

use App\Common\ValueObjects\CryptocurrencyId;
use Domain\ValueObjects\Holders;
use Domain\ValueObjects\Id;

class AssignHolders
{
    public Id $id;
    public Holders $holders;

    public function __construct(Id $id, Holders $holders)
    {
        $this->id = $id;
        $this->holders = $holders;
    }
}
