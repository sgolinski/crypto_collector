<?php

namespace App\Domain\Entity;

class ScriptsJs
{
    public const CONTENT_SELECTOR_TABLE = '#content > div.container.space-bottom-2 > div > div.card-body';
    public const CONTENT_SELECTOR_TABLE_BODY = 'table.table-hover > tbody';
    public const NAME_SELECTOR = 'tr > td:nth-child(3) > a';
    public const INFORMATION_SELECTOR = 'tr > td:nth-child(5)';
    public const ADDRESS_SELECTOR = 'tr > td:nth-child(3) > a';
    public const HOLDERS_SELECTOR = '#ContentPlaceHolder1_tr_tokenHolders > div > div.col-md-8 > div > div';
}
