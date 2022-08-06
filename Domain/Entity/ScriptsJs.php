<?php

namespace App\Domain\Entity;

class ScriptsJs
{
    public const INDEX_OF_SHOWN_ROWS = 3;
    public const CONTENT_SELECTOR_TABLE = '#content > div.container.space-bottom-2 > div > div.card-body';
    public const CONTENT_SELECTOR_TABLE_XPATH = '//*[@id="content"]/div[2]/div/div[2]';
    public const CONTENT_SELECTOR_TABLE_BODY = 'table.table-hover > tbody';
    public const CONTENT_SELECTOR_TABLE_BODY_XPATH = '//*[@id="content"]/div[2]/div/div[2]/div[2]/table/tbody/';
    public const CONTENT_SELECTOR_TABLE_BODY_XPATH_FILTERED_TD6 = '//*[@id="content"]/div[2]/div/div[2]/div[2]/table/tbody/tr/td[5]/a[contains(text(),"WBNB") or contains(text(),"USD")]';
    public const SELECTOR_SELECT_MORE_RECORDS = 'ContentPlaceHolder1_ddlRecordsPerPage';
    public const NAME_SELECTOR = 'tr > td:nth-child(3) > a';
    public const INFORMATION_SELECTOR = 'tr > td:nth-child(5)';
    public const ADDRESS_SELECTOR = 'tr > td:nth-child(3) > a';
    public const HOLDERS_SELECTOR = '#ContentPlaceHolder1_tr_tokenHolders > div > div.col-md-8 > div > div';
    public const BUTTON_SELECTOR = '#ctl00 > div.d-md-flex.justify-content-between.my-3 > ul > li:nth-child(4) > a';
}
