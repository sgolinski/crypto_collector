<?php

namespace App\Domain\Command;

use App\Common\ValueObjects\Id;

class AssignToBlackList
{
    public Id $id;

    public function __construct(Id $id)
    {
        $this->id = $id;
    }
}
