<?php

namespace App\Infrastructure\Repository;

use App\Common\ValueObjects\Address;
use App\Common\ValueObjects\Chain;
use App\Common\ValueObjects\Holders;
use App\Common\ValueObjects\Name;
use App\Common\ValueObjects\Price;
use App\Common\ValueObjects\Id;
use App\CryptocurrencyTransaction;
use App\Infrastructure\Exception\UnableToCreateCryptocurrencyTransactionException;
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

    public function add(CryptocurrencyTransaction $transaction): void
    {

        $this->db->beginTransaction();

        $sql = 'INSERT INTO potential_drop_cryptocurrency (cryptocurrency_id, name,price,chain,holders,percentage,occured_on) VALUES (?,?, ?,?,null,null,NOW())';
        try {
            $stm = $this->db->prepare(
                $sql
            );

            $stm->execute([
                $transaction->id()->asString(),
                $transaction->name()->asString(),
                $transaction->price()->asFloat(),
                $transaction->chain()->asString(),
            ]);
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
            throw new UnableToCreateCryptocurrencyTransactionException($e);
        }
    }


    public function update($id, $price): void
    {
        $sql = 'UPDATE potential_drop_cryptocurrency SET price = ? AND occured_on = NOW() WHERE  cryptocurrency_id = ? AND isBlacklisted = false ';
        $stm = $this->db->prepare($sql);

        $stm->execute();
    }

    public function byComplete(Name $name): mixed
    {
        $sql = 'SELECT isComplete, isBlacklisted FROM potential_drop_cryptocurrency WHERE name = ?';
        $stm = $this->db->prepare($sql);
        $stm->execute([$name]);

        return $stm->fetch();
    }

    public function byName(Name $name): bool
    {
        $stm = $this->db->prepare(
            'SELECT cryptocurrency_id FROM potential_drop_cryptocurrency WHERE name = :name'
        );
        $stm->bindParam(':name', $name);

        $stm->execute();

        return $stm->rowCount();
    }

    public function byId(string $id): CryptocurrencyTransaction
    {
        $stm = $this->db->prepare(
            'SELECT * FROM potential_drop_cryptocurrency WHERE cryptocurrency_id = :id'
        );
        $stm->bindParam(':id', $id);

        $stm->execute();

        return $this->format($stm->fetch());
    }

    public function addToBlackList(Id $id): void
    {
        $sql = 'UPDATE potential_drop_cryptocurrency SET isBlacklisted = true WHERE  cryptocurrency_id = ? AND isBlacklisted=false';
        $stm = $this->db->prepare($sql);
        $stm->execute([$id]);
    }

    public function updateHolders(Id $id, Holders $holders)
    {
        $sql = 'UPDATE potential_drop_cryptocurrency SET holders = :holders, occured_on = NOW(), isComplete = true WHERE  cryptocurrency_id = :id AND isComplete = false AND isBlacklisted=false ';
        $stm = $this->db->prepare($sql);
        $stm->bindParam(':holders', $holders);
        $stm->bindParam(':id', $id);
        $stm->execute();
    }

    public function updateAlert(Id $id): void
    {
        $sql = 'UPDATE potential_drop_cryptocurrency SET isAlertSent =true , occured_on = NOW() WHERE  cryptocurrency_id = :id AND isComplete =true AND isBlacklisted=false ';
        $stm = $this->db->prepare($sql);
        $stm->bindParam(':id', $id);
        $stm->execute();
    }


    public function findAllNotComplete(): array
    {
        $sql = 'SELECT * FROM potential_drop_cryptocurrency WHERE isComplete = false AND isBlacklisted = false ';
        $stm = $this->db->prepare($sql);
        $stm->execute();
        return $this->createCollectionFrom($stm->fetchAll());
    }

    public function findAllCompletedNotSent(): array
    {
        $sql = 'SELECT * FROM potential_drop_cryptocurrency WHERE isComplete = true AND isBlacklisted = false AND isAlertSent = false';
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

    private function format(mixed $result): ?CryptocurrencyTransaction
    {
        if (empty($result)) {
            return null;
        }


        $cryptocurrency_id = null;

        if (isset($result['cryptocurrency_id'])) {
            $cryptocurrency_id = Id::fromString($result['cryptocurrency_id']);
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

        $transactionId = Id::fromString($cryptocurrency_id);
        $transaction = CryptocurrencyTransaction::fromParams(
            $transactionId,
            $address,
            $name,
            $price,
            $chain,
        );

        return $transaction;
    }
}
