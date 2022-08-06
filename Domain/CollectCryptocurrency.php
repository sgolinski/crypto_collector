<?php

namespace App\Domain;

use App\Application\Service\CrawlerDexTracker;
use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Price;
use App\Domain\Command\ChangePriceCommand;
use App\Domain\Command\CryptocurrencyRegisterCommand;
use App\Domain\CommandHandler\ChangePriceCommandHandler;
use App\Domain\CommandHandler\CryptocurrencyRegisterCommandHandler;
use App\Domain\Entity\Information;
use App\Domain\Entity\Names;
use App\Domain\Entity\ScriptsJs;
use App\Domain\Entity\Urls;
use App\Domain\Query\CryptocurrencyQueryByAddress;
use App\Domain\Query\CryptocurrencyQueryByName;
use App\Domain\QueryHandler\CryptocurrencyQueryHandlerByAddress;
use App\Domain\QueryHandler\CryptocurrencyQueryHandlerByName;
use ArrayIterator;
use Exception;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use InvalidArgumentException;

use Symfony\Component\Panther\DomCrawler\Crawler as RemoteCrawler;

class CollectCryptocurrency extends CrawlerDexTracker implements Crawler
{
    public function invoke(): void
    {
        try {
            $this->startClient(Urls::URL);
            usleep(3000);
            $this->scrappingData();
        } catch (Exception $exception) {
            echo $exception->getMessage() . PHP_EOL;
            $this->client->close();
            $this->client->quit();
            $this->invoke();
        }
    }

    private function scrappingData(): void
    {
        $lastUri = '';
        for ($i = 0; $i < 100; $i++) {
            if ($i % 29 == 0) {
                sleep(12);
                $this->client->reload();
            }
            if ($lastUri == 'https://bscscan.com/busy') {
                sleep(12);
                $this->client->restart();
            }

            $currentUri = Urls::URL_CON . $i;
            if ($currentUri === $lastUri) {
                continue;
            }

            try {
                echo 'Start getting content for page ' . $i . ' ' . date("F j, Y, g:i:s a") . PHP_EOL;
                $data = $this->getElementsFromWebsite($currentUri);
                if ($data !== null) {
                    $this->createCryptocurrencyFrom($data);
                }
                $lastUri = $this->client->getCrawler()->getUri();
                echo $lastUri . PHP_EOL;
                echo 'Finish getting content for page ' . $i . ' ' . date("F j, Y, g:i:s a") . PHP_EOL;
            } catch (Exception $exception) {
                echo $exception->getMessage();
                continue;
            }
        }
    }

    private function getElementsFromWebsite($url): ?ArrayIterator
    {
        $this->client->get($url);
        $this->client->refreshCrawler();
        try {
            $elements = $this->client->getCrawler()
                ->filter(ScriptsJs::CONTENT_SELECTOR_TABLE)
                ->filter(ScriptsJs::CONTENT_SELECTOR_TABLE_BODY)
                ->children()
                ->getIterator();
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

        return $elements;
    }

    private function createCryptocurrencyFrom(
        ?ArrayIterator $webElements
    ): void
    {
        foreach ($webElements as $webElement) {
            try {
                assert($webElement instanceof RemoteWebElement);
                $information = $this->getInformationFrom($webElement);

                $service = Information::fromString($information);
                $chain = $service->getChain();
                $price = $service->getPrice();
                $name = $this->getNameFrom($webElement);
                $name = Name::fromString($name);
                $this->ensureTokenNameIsNotBlacklisted($name);
                $cryptocurrencyExist = $this->findCryptocurrencyByName($name);

                if ($cryptocurrencyExist) {
                    echo 'Token already exist ' . date("F j, Y, g:i:s a") . PHP_EOL;
                    continue;
                }

                $address = $this->getAddressFrom($webElement);
                $address = Address::fromString($address);
                $this->ensureTokenAddressIsNotinDb($address);


                $this->emmitCryptocurrencyRegisterEvent($address, $name, $price, $chain);
            } catch (InvalidArgumentException $exception) {
                continue;
            }
        }
    }

    private function findCryptocurrencyByName(Name $name): bool
    {
        $cryptocurrencyQuery = new CryptocurrencyQueryByName($name);
        $cryptocurrencyQueryByNameHandler = new CryptocurrencyQueryHandlerByName($this->cryptocurrencyRepository);
        return $cryptocurrencyQueryByNameHandler->__invoke($cryptocurrencyQuery);
    }

    private function findCryptocurrencyByAddress(Address $address): bool
    {
        $cryptocurrencyQuery = new CryptocurrencyQueryByAddress($address);
        $cryptocurrencyQueryByAddressHandler = new CryptocurrencyQueryHandlerByAddress($this->cryptocurrencyRepository);
        return $cryptocurrencyQueryByAddressHandler->__invoke($cryptocurrencyQuery);
    }

    private function emmitCryptocurrencyRegisterEvent(Address $address, Name $name, Price $price, Chain $chain): void
    {
        echo 'Start emitting event ' . date("F j, Y, g:i:s a") . PHP_EOL;
        $registerCryptocurrencyCommand = new CryptocurrencyRegisterCommand($address, $name, $price, $chain);
        $registerCryptocurrencyCommandHandler = new CryptocurrencyRegisterCommandHandler($this->cryptocurrencyRepository);
        $registerCryptocurrencyCommandHandler->handle($registerCryptocurrencyCommand);

    }

    private function emmitCryptocurrencyPriceWasChangedEvent(CryptocurrencyId $id, Price $price): void
    {
        $changePriceCommand = new ChangePriceCommand($id, $price);
        $changePriceCommandHandler = new ChangePriceCommandHandler($this->cryptocurrencyRepository);
        $changePriceCommandHandler->handle($changePriceCommand);
    }


    private function ensureTokenNameIsNotBlacklisted(
        string $name
    ): void
    {
        if (in_array($name, NAMES::BLACKLISTED_NAMES_FOR_CRYPTOCURRENCIES)) {
            throw new InvalidArgumentException('Currency is on the blacklist ');
        }
    }

    /**
     * @param RemoteWebElement $webElement
     * @return string
     */
    private function getNameFrom(RemoteWebElement $webElement): string
    {
        return $webElement
            ->findElement(WebDriverBy::cssSelector(ScriptsJs::NAME_SELECTOR))
            ->getText();
    }

    /**
     * @param RemoteWebElement $webElement
     * @return string
     */
    private function getInformationFrom(RemoteWebElement $webElement): string
    {
        return $webElement
            ->findElement(WebDriverBy::cssSelector(ScriptsJs::INFORMATION_SELECTOR))
            ->getText();
    }

    /**
     * @param RemoteWebElement $webElement
     * @return string|true|null
     */
    private function getAddressFrom(RemoteWebElement $webElement): string|bool|null
    {
        return $webElement
            ->findElement(WebDriverBy::cssSelector(ScriptsJs::ADDRESS_SELECTOR))
            ->getAttribute('href');
    }

    private function ensureTokenAddressIsNotinDb(Address $address)
    {
        if ($this->findCryptocurrencyByAddress($address)) {
            echo 'Token already exist ' . date("F j, Y, g:i:s a") . PHP_EOL;
            throw new InvalidArgumentException('Address already in DB ');
        }
    }
}
