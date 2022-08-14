<?php

namespace App\Domain\Query;

use App\Domain\ValueObjects\Url;

class QueryHolders
{
    private Url $url;

    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    public function url(): Url
    {
        return $this->url;
    }
}