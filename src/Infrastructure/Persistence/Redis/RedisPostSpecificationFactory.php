<?php

namespace App\Infrastructure\Persistence\Redis;

class RedisPostSpecificationFactory
{
    public function createLatestPosts(\DateTime $since)
    {
        return new RedisLatestPostSpecification($since);
    }
}
