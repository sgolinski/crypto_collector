<?php

namespace App\Domain;

interface Crawler
{
    public function invoke(): void;
}
