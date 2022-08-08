<?php

namespace App\Domain\QueryHandler;

use App\Common\ValueObjects\Url;
use App\Domain\Query\DownloadWebElements;
use App\Domain\Query\FindHolders;
use App\Infrastructure\Repository\RemoteCryptocurrencyRepository;
use ArrayIterator;

class DownloadWebElementsHandler
{
    private RemoteCryptocurrencyRepository $repository;

    public function __construct(RemoteCryptocurrencyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DownloadWebElements $downloadWebElements): ArrayIterator
    {
        return $this->repository->findElements($downloadWebElements->url());
    }
}