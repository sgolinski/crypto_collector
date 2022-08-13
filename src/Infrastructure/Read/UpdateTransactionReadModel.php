<?php

namespace App\Infrastructure\Read;

use App\CryptocurrencyRepository;
use App\Event\PriceChanged;

class UpdateTransactionReadModel
{

    private CryptocurrencyRepository $readModelRepository;

    /**
     * @param CryptocurrencyRepository $readModelRepository
     */
    public function __construct(CryptocurrencyRepository $readModelRepository)
    {
        $this->readModelRepository = $readModelRepository;
    }

    public function whenPriceWasChanged(PriceChanged $changed)
    {
        $readModel = $this->readModelRepository->byId(
            $changed->id()
        );
        $readModel->changePrice($changed->newPrice());
        $this->readModelRepository->save($readModel);
    }
}