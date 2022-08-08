<?php

namespace App\Application\Service;

use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Holders;
use App\Domain\Command\AssignToBlackList;
use App\Domain\CommandHandler\AssignHoldersHandler;
use App\Domain\CommandHandler\AssignToBlackListHandler;
use App\Domain\Query\FindAllNotCompleteCryptocurrencyTransactions;
use App\Domain\QueryHandler\AllCryptocurrenciesNotCompleteQueryHandler;
use InvalidArgumentException;


class AssignHolders
{
    public function invoke(): void
    {

    }

    private function getNotCompletedCryptocurrencies(): array
    {
        $cryptocurrenciesQuery = new FindAllNotCompleteCryptocurrencyTransactions();
        $cryptocurrenciesQueryHandler = new AllCryptocurrenciesNotCompleteQueryHandler($this->cryptocurrencyRepository);
        $notCompletedTokens = $cryptocurrenciesQueryHandler->__invoke($cryptocurrenciesQuery);
        return $notCompletedTokens;
    }


    private function emmitCryptocurrencyHoldersWhereAssigned(CryptocurrencyId $id, Holders $holders): void
    {
        $assignHoldersCommand = new AssignHolders($id, $holders);
        $assignHoldersCommandHandler = new AssignHoldersHandler($this->cryptocurrencyRepository);
        $assignHoldersCommandHandler->handle($assignHoldersCommand);
    }

    protected function ensureNumberOfHoldersIsBiggerThen(
        CryptocurrencyId $id,
        int              $holders
    ): void {
        if ($holders < Holders::MIN_AMOUNT_HOLDERS) {
            $assignHoldersCommand = new AssignToBlackList($id);
            $assignHoldersCommandHandler = new  AssignToBlackListHandler($this->cryptocurrencyRepository);
            $assignHoldersCommandHandler->handle($assignHoldersCommand);

            throw new InvalidArgumentException('Expected number of holders it to low');
        }
    }

}
