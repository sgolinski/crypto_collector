<?php

namespace App\Domain\QueryHandler;

use App\Common\ValueObjects\Url;
use App\Domain\Query\FindHolders;
use App\Infrastructure\Repository\RemoteCryptocurrencyRepository;

class FindHoldersHandler
{
    private RemoteCryptocurrencyRepository $repository;

    public function __construct(RemoteCryptocurrencyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(FindHolders $findHolders): string
    {
        return $this->repository->findOneElementOn($findHolders->url());
    }
}