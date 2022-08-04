<?php

namespace App\Infrastructure\Persistence\Redis;

class RedisLatestPostSpecification implements RedisPostSpecification
{
    private $since;

    public function __construct(\DateTime $since)
    {
        $this->since = $since;
    }

    public function specifies(Post $aPost)
    {
        return $aPost->createdAt() > $this->since;
    }
}
