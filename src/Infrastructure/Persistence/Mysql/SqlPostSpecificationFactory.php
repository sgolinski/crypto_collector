<?php

namespace App\Infrastructure\Persistence\Mysql;

class SqlPostSpecificationFactory implements PostSpecificationFactory
{
    public function createLatestPosts(\DateTime $since)
    {
        return new SqlLatestPostSpecification($since);
    }
}
