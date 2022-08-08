<?php

namespace App\Application\Service;

use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Price;
use App\Domain\Command\ChangePrice;
use App\Domain\Command\CreatePotentialDropCryptoCurrencyCommand;
use App\Domain\Command\RegisterTransaction;
use App\Domain\CommandHandler\ChangePriceHandler;
use App\Domain\CommandHandler\CreatePotentialDropCryptoCurrencyCommandHandler;
use App\Domain\CommandHandler\RegisterTransactionHandler;
use App\Domain\Crawler;
use App\Domain\Entity\Transaction;
use App\Domain\Entity\Currency;
use App\Domain\Entity\Information;
use App\Domain\Entity\Names;
use App\Domain\Entity\PotentialDumpAndPumpTransaction;
use App\Domain\Entity\ScriptsJs;
use App\Domain\Entity\Urls;
use App\Domain\Query\FindCryptocurrencyTransactionByAddress;
use App\Domain\Query\FindCryptocurrencyTransactionByName;
use App\Domain\QueryHandler\FindCryptocurrencyTransactionByAddressHandler;
use App\Domain\QueryHandler\FindCryptocurrencyTransactionByNameHandler;
use App\Infrastructure\Repository\CryptocurrencyRepository;
use App\Infrastructure\Repository\PDOCryptocurrencyRepository;
use ArrayIterator;
use Exception;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use InvalidArgumentException;


/**
 * @property array $potentialDropObjects
 */
class CollectCryptocurrency
{


    private function scrappingData(): void
    {

//        $lastUri = 'https://bscscan.com/busy';
//        $currentUri = Urls::URL_CON . 1;
//        $lastUri = $this->client->getCrawler()->getUri();
//        if ($currentUri === $lastUri) {
//
//            $this->createCryptocurrencyFrom($data);
//        }

    }


    private function ensurePriceIsHighEnough(
        Chain $chain,
        Price $price
    ): void
    {
        if ($price->asFloat() < Currency::ALLOWED_PRICE_PER_TOKEN[$chain->__toString()]) {
            throw new InvalidArgumentException();
        }

    }


}
