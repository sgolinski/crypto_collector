<?php

namespace App\Domain\Query;

use ArrayIterator;

class QueryTransactionsFromWebElements
{
    private ArrayIterator $elements;

    public function __construct(ArrayIterator $elements)
    {
        $this->elements = $elements;
    }

    public function elements(): ArrayIterator
    {
        return $this->elements;
    }
}
