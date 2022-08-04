<?php

namespace App\Infrastructure\Persistence\Mysql;

interface SqlPostSpecification
{
    /**
     * @return string
     */
    public function toSqlClauses();
}
