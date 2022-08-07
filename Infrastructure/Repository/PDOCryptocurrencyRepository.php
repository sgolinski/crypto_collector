<?php

namespace App\Infrastructure\Repository;

use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\CryptocurrencyId;
use App\Common\ValueObjects\Holders;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Percentage;
use App\Common\ValueObjects\Price;
use App\Domain\Entity\Cryptocurrency;
use App\Domain\Entity\Token;
use App\Infrastructure\Exception\UnableToCreateCryptocurrencyException;
use DateTimeImmutable;
use Exception;
use PDO;
use PDOException;

class PDOCryptocurrencyRepository implements CryptocurrencyRepository
{
    private $db;

    public function __construct()
    {
        try {
            $this->db = new PDO("pgsql:host=192.168.178.36;port=5432;dbname=crypto", 'root', 'alerts', array(
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', //after php5.3.6
            ));
            //$p->exec('SET NAMES utf8');
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
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

    public function byId(CryptocurrencyId $id): bool
    {
        $stm = $this->db->prepare(
            'SELECT name FROM single_cryptocurrency WHERE cryptocurrency_id = :id'
        );
        $stm->bindParam(':id', $id);
        return $stm->execute();
    }


    public function update($id, $price): void
    {
        $sql = 'UPDATE single_cryptocurrency SET price = ? AND occured_on = NOW() WHERE  cryptocurrency_id = ? AND isBlacklisted = false ';
        $stm = $this->db->prepare($sql);

        $stm->execute();
    }

    public function byComplete(Name $name): mixed
    {
        $sql = 'SELECT isComplete, isBlacklisted FROM single_cryptocurrency WHERE name = ?';
        $stm = $this->db->prepare($sql);
        $stm->execute([$name]);

        return $stm->fetch();
    }

    public function byName(Name $name): bool
    {
        $stm = $this->db->prepare(
            'SELECT cryptocurrency_id FROM single_cryptocurrency WHERE name = :name'
        );
        $stm->bindParam(':name', $name);

        $stm->execute();

        return $stm->rowCount();
    }

    public function byAddress(Address $address): bool
    {
        $stm = $this->db->prepare(
            'SELECT cryptocurrency_id FROM single_cryptocurrency WHERE address = :address'
        );
        $stm->bindParam(':address', $address);

        $stm->execute();

        return $stm->rowCount();
    }

    public function addToBlackList(CryptocurrencyId $id): void
    {
        $sql = 'UPDATE single_cryptocurrency SET isBlacklisted = true WHERE  cryptocurrency_id = ? AND isBlacklisted=false';
        $stm = $this->db->prepare($sql);
        $stm->execute([$id]);
    }

    public function updateHolders(CryptocurrencyId $id, Holders $holders)
    {
        $sql = 'UPDATE single_cryptocurrency SET holders = :holders, occured_on = NOW(), isComplete = true WHERE  cryptocurrency_id = :id AND isComplete = false AND isBlacklisted=false ';
        $stm = $this->db->prepare($sql);
        $stm->bindParam(':holders', $holders);
        $stm->bindParam(':id', $id);
        $stm->execute();
    }

    public function updateAlert(CryptocurrencyId $id): void
    {
        $sql = 'UPDATE single_cryptocurrency SET isAlertSent =true , occured_on = NOW() WHERE  cryptocurrency_id = :id AND isComplete =true AND isBlacklisted=false ';
        $stm = $this->db->prepare($sql);
        $stm->bindParam(':id', $id);
        $stm->execute();
    }


    public function findAllNotComplete(): array
    {
        $sql = 'SELECT * FROM single_cryptocurrency WHERE isComplete = false AND isBlacklisted = false ';
        $stm = $this->db->prepare($sql);
        $stm->execute();
        return $this->createCollectionFrom($stm->fetchAll());
    }

    public function findAllCompletedNotSent(): array
    {
        $sql = 'SELECT * FROM single_cryptocurrency WHERE isComplete = true AND isBlacklisted = false AND isAlertSent = false';
        $stm = $this->db->prepare($sql);
        $stm->execute();
        return $this->createCollectionFrom($stm->fetchAll());
    }

    public function createCollectionFrom(array $notCompleted): array
    {
        $returnArr = [];
        foreach ($notCompleted as $value) {
            $returnArr[] = $this->format($value);
        }

        return $returnArr;
    }

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
        $isComplete = false;
        if (isset($result['isComplete'])) {
            $isComplete = (bool)$result['isComplete'];
        }
        $isBlacklisted = false;
        if (isset($result['isBlacklisted'])) {
            $isBlacklisted = (bool)$result['isBlacklisted'];
        }

        $cryptocurrency = Token::create($cryptocurrency_id);
        $cryptocurrency->fromParams(
            $address,
            $name,
            $chain,
            $price,
            $created,
            $isComplete,
            $isBlacklisted
        );

        return $cryptocurrency;
    }


}
