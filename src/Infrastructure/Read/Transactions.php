<?php

namespace App\Infrastructure\Read;

interface Transactions
{
    public function listAllAvailableEbooks(): array;
}