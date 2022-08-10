<?php

namespace App\Domain\Command;

use App\Common\ValueObjects\TransactionId;

class AssignToBlackList
{
    public TransactionId $id;

    public function __construct(TransactionId $id)
    {
        $this->id = $id;
    }
}
