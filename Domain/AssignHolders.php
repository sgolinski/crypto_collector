<?php

namespace App\Domain;

use App\Application\Service\CrawlerDexTracker;
use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Holders;
use App\Common\ValueObjects\Url;
use App\Domain\Command\AssignHoldersCommand;
use App\Domain\Command\AssignToBlackListCommand;
use App\Domain\CommandHandler\AssignHoldersCommandHandler;
use App\Domain\CommandHandler\AssignToBlackListCommandHandler;
use App\Domain\Entity\Urls;
use App\Domain\Query\AllCryptocurrenciesNotCompleteQuery;
use App\Domain\QueryHandler\AllCryptocurrenciesNotCompleteQueryHandler;
use InvalidArgumentException;
use Symfony\Component\Panther\Client as PantherClient;

class AssignHolders extends CrawlerDexTracker implements Crawler
{
    public function invoke(): void
    {
        $this->completeDataForCryptocurrencies();
    }

    public function completeDataForCryptocurrencies(): void
    {
        $tokens = $this->getNotCompletedCryptocurrencies();
        foreach ($tokens as $notCompletedToken) {
            try {
                $url = Url::fromString(Urls::URL_TOKEN . $notCompletedToken->address());
                $this->startClient($url->__toString());
                $holdersString = $this->client->getCrawler()
                    ->filter('#ContentPlaceHolder1_tr_tokenHolders > div > div.col-md-8 > div > div')
                    ->getText();
                $holdersNumber = (int)str_replace(',', "", explode(' ', $holdersString)[0]);
                $this->ensureNumberOfHoldersIsBiggerThen($notCompletedToken->id(), $holdersNumber);
                $holders = Holders::fromInt($holdersNumber);
                $this->emmitCryptocurrencyHoldersWhereAssigned($notCompletedToken->id(), $holders);
            } catch (InvalidArgumentException $exception) {
                $this->client->close();
                $this->client->quit();
                continue;
            }
        }
    }

    private function getNotCompletedCryptocurrencies(): array
    {
        $cryptocurrenciesQuery = new AllCryptocurrenciesNotCompleteQuery();
        $cryptocurrenciesQueryHandler = new AllCryptocurrenciesNotCompleteQueryHandler($this->cryptocurrencyRepository);
        $notCompletedTokens = $cryptocurrenciesQueryHandler->__invoke($cryptocurrenciesQuery);
        return $notCompletedTokens;
    }


    private function emmitCryptocurrencyHoldersWhereAssigned(CryptocurrencyId $id, Holders $holders): void
    {
        $assignHoldersCommand = new AssignHoldersCommand($id, $holders);
        $assignHoldersCommandHandler = new AssignHoldersCommandHandler($this->cryptocurrencyRepository);
        $assignHoldersCommandHandler->handle($assignHoldersCommand);
    }

    protected function ensureNumberOfHoldersIsBiggerThen(
        CryptocurrencyId $id,
        int              $holders
    ): void {
        if ($holders < Holders::MIN_AMOUNT_HOLDERS) {
            $assignHoldersCommand = new AssignToBlackListCommand($id);
            $assignHoldersCommandHandler = new  AssignToBlackListCommandHandler($this->cryptocurrencyRepository);
            $assignHoldersCommandHandler->handle($assignHoldersCommand);

            throw new InvalidArgumentException('Expected number of holders it to low');
        }
    }

    protected function getCrawlerForWebsite(
        string $url
    ): void {
        $this->client = PantherClient::createChromeClient();
        $this->client->start();
        $this->client->get($url);
        usleep(30000);
        $this->client->refreshCrawler();
        usleep(30000);
    }
}
