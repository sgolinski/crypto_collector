<?php

namespace App\Domain\Query;

use App\Common\ValueObjects\Url;

class DownloadWebElements
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