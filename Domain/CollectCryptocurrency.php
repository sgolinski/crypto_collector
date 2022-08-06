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
use App\Domain\Model\Cryptocurrency;
use App\Domain\Query\CryptocurrencyQueryByName;
use App\Domain\QueryHandler\CryptocurrencyQueryHandlerByName;
use App\Factory;
use ArrayIterator;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\UnexpectedTagNameException;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use InvalidArgumentException;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\WebDriver\PantherWebDriverExpectedCondition;
use Symfony\Component\Panther\DomCrawler\Crawler as RemoteCrawler;

class CollectCryptocurrency extends CrawlerDexTracker implements Crawler
{
    public function invoke(): void
    {
        try {
            echo "Start crawling " . date("F j, Y, g:i:s a") . PHP_EOL;
            $this->startClient(Urls::URL);
            //$this->changeOnWebsiteToShowMoreRecords();
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
        for ($i = 0; $i < 200; $i++) {
            if ($i % 29 == 0) {
                sleep(12);
                $this->client->reload();
            }
            if ($lastUri == 'https://bscscan.com/busy') {
                $this->client->restart();
            }

            $currentUri = Urls::URL_CON . $i;
            if ($currentUri === $lastUri) {
                continue;
            }
            echo 'Start getting content for page ' . $i . ' ' . date("F j, Y, g:i:s a") . PHP_EOL;
            try {
                $data = $this->getElementsFromWebsite($currentUri);
                $this->createCryptocurrencyFrom($data);
                $lastUri = $this->client->getCrawler()->getUri();
                echo $lastUri . PHP_EOL;
                echo 'Finish getting content for page ' . $i . ' ' . date("F j, Y, g:i:s a") . PHP_EOL;
            } catch (Exception) {
                $this->client->reload();
                continue;
            }
        }
    }


    private function getElementsFromWebsite($url): ?ArrayIterator
    {
        $this->client->get($url);
        $this->client->refreshCrawler();
        usleep(1000);
        try {
            $elements = $this->client->getCrawler()
                ->filterXPath(ScriptsJs::CONTENT_SELECTOR_TABLE_BODY_XPATH_FILTERED)
                ->reduce(function (RemoteCrawler $node) {

                    $information = explode(" ", $node->text());
                    $chain = $information[1];
                    $price = round((float)$information[0], 4);
                    switch ($chain) {
                        case'WBNB':
                            if ($price > 0.01) {
                                return $node->ancestors();
                            }
                            return false;
                        case 'BUSD':
                        case'USDC':
                        case 'BSC-USD':
                        case 'USDT':
                            if ($price > 2522.96) {
                                return $node->ancestors();
                            }
                            return false;
                        default:
                            return false;
                    }

                })
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
                echo 'Start getting name ' . date("F j, Y, g:i:s a") . PHP_EOL;
                $name = $this->getNameFrom($webElement);
                $name = Name::fromString($name);
                $this->ensureTokenNameIsNotBlacklisted($name);
                echo 'Finish getting name ' . date("F j, Y, g:i:s a") . PHP_EOL;

                echo 'Start getting existing token ' . date("F j, Y, g:i:s a") . PHP_EOL;
                $cryptocurrencyExist = $this->findCryptocurrencyBy($name);
                echo 'Finish getting existing token ' . date("F j, Y, g:i:s a") . PHP_EOL;

                echo 'Start getting existing address ' . date("F j, Y, g:i:s a") . PHP_EOL;

                if ($cryptocurrencyExist) {
                    continue;
                }
                $address = $this->getAddressFrom($webElement);
                $address = Address::fromString($address);
                echo 'Finish getting existing address ' . date("F j, Y, g:i:s a") . PHP_EOL;
                echo 'Start emiting event ' . date("F j, Y, g:i:s a") . PHP_EOL;
                $this->emmitCryptocurrencyRegisterEvent($address, $name, $price, $chain);


            } catch (InvalidArgumentException $exception) {
                echo $exception->getMessage();
                continue;
            }
        }
    }

    private function findCryptocurrencyBy(Name $name): bool
    {
        $cryptocurrencyQuery = new CryptocurrencyQueryByName($name);
        $cryptocurrencyQueryByNameHandler = new CryptocurrencyQueryHandlerByName($this->cryptocurrencyRepository);
        $cryptocurrency = $cryptocurrencyQueryByNameHandler->__invoke($cryptocurrencyQuery);
        return $cryptocurrency;
    }

    private function emmitCryptocurrencyRegisterEvent(Address $address, Name $name, Price $price, Chain $chain): void
    {
        $registerCryptocurrencyCommand = new CryptocurrencyRegisterCommand($address, $name, $price, $chain);
        $registerCryptocurrencyCommandHandler = new CryptocurrencyRegisterCommandHandler($this->cryptocurrencyRepository);
        $registerCryptocurrencyCommandHandler->handle($registerCryptocurrencyCommand);
        echo 'Finish emiting event ' . date("F j, Y, g:i:s a") . PHP_EOL;
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
            throw new InvalidArgumentException('Currency is on the blacklist');
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
}


#content > div.container.space-bottom-2 > div > div.card-body > div.table-responsive.mb-2.mb-md-0 > table > tbody > tr:nth-child(8) > td:nth-child(5) > a