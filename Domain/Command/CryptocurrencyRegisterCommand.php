<?php

namespace App\Domain\Command;

use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Holders;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Percentage;
use App\Common\ValueObjects\Price;
use DateTimeImmutable;

class CryptocurrencyRegisterCommand
{
    public CryptocurrencyId $id;
    public ?Holders $holders;
    public ?Percentage $percentage;
    public DateTimeImmutable $occured_on;


    public function __construct(public Address $address, Name $name, Price $price, Chain $chain)
    {
    }
}
