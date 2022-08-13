<?php

namespace App\Command;

use App\ValueObjects\Chain;
use App\ValueObjects\Id;
use App\ValueObjects\Name;
use App\ValueObjects\Price;

interface CreatePumpAndDump
{

    public function id();

    public function name();

    public function price();

    public function chain();
}