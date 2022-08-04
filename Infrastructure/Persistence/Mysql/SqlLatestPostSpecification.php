<?php

namespace App\Infrastructure\Persistence\Mysql;

class SqlLatestPostSpecification implements SqlPostSpecification
{
    private $since;

    public function __construct(\DateTime $since)
    {
        $this->since = $since;
    }

    public function toSqlClauses()
    {
        return "created_at > '" . $this->since->format('Y-m-d H:i:s') . "'";
    }
}
