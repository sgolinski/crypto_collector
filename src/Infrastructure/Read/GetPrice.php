<?php

namespace App\Infrastructure\Read;

use Domain\ValueObjects\Id;

interface GetPrice
{
    public function ofTransaction(Id $id): float;
}