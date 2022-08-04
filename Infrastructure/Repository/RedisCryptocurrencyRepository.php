<?php

namespace App\Infrastructure\Repository;

use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Holders;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Percentage;
use App\Common\ValueObjects\Price;
use App\Domain\Model\Cryptocurrency;
use App\Infrastructure\Exception\UnableToCreateCryptocurrencyException;
use DateTimeImmutable;
use Exception;
use Predis\Client;

class RedisCryptocurrencyRepository implements CryptocurrencyRepository
{
    private $db;

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

    public function add(Cryptocurrency $cryptocurrency): void
    {
        $this->db->beginTransaction();

        $sql = 'INSERT INTO single_cryptocurrency (cryptocurrency_id,address, name,price,chain,holders,percentage,occured_on) VALUES (?,?, ?,?,?,null,null,NOW())';
        try {
            $stm = $this->db->prepare(
                $sql
            );

            $stm->execute([
                $cryptocurrency->id(),
                $cryptocurrency->address(),
                $cryptocurrency->name(),
                $cryptocurrency->price(),
                $cryptocurrency->chain(),
            ]);
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
            throw new UnableToCreateCryptocurrencyException($e);
        }
    }

    public function byId(CryptocurrencyId $id): Cryptocurrency
    {
        $stm = $this->db->prepare(
            'SELECT * FROM single_cryptocurrency WHERE id = ?'
        );

        $stm->execute([$id->id()]);

        return $stm->fetch();
    }


    public function update($id, $price): void
    {
        $sql = 'UPDATE single_cryptocurrency SET price = ? AND occured_on = NOW() WHERE  cryptocurrency_id = ? AND  ';
        $stm = $this->db->prepare($sql);
        $stm->execute();
    }

    public function byComplete($name): mixed
    {
        $sql = 'SELECT isComplete, isBlacklisted FROM single_cryptocurrency WHERE name = ?';

        $stm = $this->db->prepare($sql);

        $stm->execute([$name]);

        return $stm->fetch();
    }

    public function byName(Name $name): ?Cryptocurrency
    {
        $stm = $this->db->prepare(
            'SELECT * FROM single_cryptocurrency WHERE name = ? AND isComplete = 1 AND isBlacklisted = 0'
        );

        $stm->execute([$name]);

        return $this->format($stm->fetch());
    }

    /**
     * @throws Exception
     */
    private function format(mixed $result): ?Cryptocurrency
    {
        if (empty($result)) {
            return null;
        }

        $cryptocurrency_id = null;

        if (isset($result['cryptocurrency_id'])) {
            $cryptocurrency_id = CryptocurrencyId::fromString($result['cryptocurrency_id']);
        }

        $address = null;
        if (isset($result['address'])) {
            $address = Address::fromString($result['address']);
        }

        $name = null;
        if (isset($result['name'])) {
            $name = Name::fromString($result['name']);
        }

        $price = null;
        if (isset($result['price'])) {
            $price = Price::fromFloat($result['price']);
        }

        $chain = null;
        if (isset($result['chain'])) {
            $chain = Chain::fromString($result['chain']);
        }
        $holders = null;
        if (isset($result['holders'])) {
            $holders = Holders::fromInt($result['holders']);
        }

        $percentage = null;
        if (isset($result['percentage'])) {
            $percentage = Percentage::fromFloat($result['percentage']);
        }

        $created = null;
        if (isset($result['occured_on'])) {
            $created = new DateTimeImmutable($result['occured_on']);
        }


        $cryptocurrency = Cryptocurrency::create($cryptocurrency_id);
        $cryptocurrency->fromParams(
            $address,
            $name,
            $chain,
            $price,
            $created,
            $holders,
        );

        return $cryptocurrency;
    }
}
