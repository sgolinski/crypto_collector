<?php

namespace App\Infrastructure\Repository;

use Exception;
use Predis\Client;

class RedisCryptocurrencyRepository
{
    private Client $db;

    public function __construct()
    {
        try {
            $this->db = new Client([
                'host' => '127.0.0.1' // docker container name, app_redis
            ]);
        } catch (Exception $exception) {
            echo 'Not connected';
        }
    }

    /**
     * @return Client
     */
    public function getDb(): Client
    {
        return $this->db;
    }
}
