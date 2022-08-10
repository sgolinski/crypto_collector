<?php

namespace App\Domain\Query;

use App\Common\ValueObjects\Name;
use InvalidArgumentException;

class QueryTransactionByName
{
    private Name $name;

    public function __construct(Name $name)
    {
        $this->name = $name;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function setName($name): void
    {
        if ($this->name === null) {
            throw new InvalidArgumentException('Cannot be empty');
        }
        $this->name = $name;
    }
}
