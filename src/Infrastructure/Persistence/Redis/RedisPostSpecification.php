<?php

namespace App\Infrastructure\Persistence\Redis;

interface RedisPostSpecification
{
    /**
     * @return boolean
     */
    public function specifies(Post $aPost);
}
