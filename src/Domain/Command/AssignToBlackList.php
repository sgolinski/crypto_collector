<?php

namespace App\Domain\Command;

use App\Domain\ValueObjects\Id;

class AssignToBlackList
{
    public Id $id;

    public function __construct(Id $id)
    {
        $this->id = $id;
    }
}
