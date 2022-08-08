<?php

namespace App\Domain\Entity;

use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Price;
use App\Common\ValueObjects\TransactionId;

interface Transaction
{
    public function id(): TransactionId;

    public function address(): Address;

    public function name(): Name;

    public function price(): Price;

    public function chain(): Chain;
}