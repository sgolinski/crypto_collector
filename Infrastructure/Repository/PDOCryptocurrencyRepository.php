<?php

namespace Infrastructure\Repository;

use App\CryptocurrencyRepository;
use App\Transaction;
use DateTimeImmutable;
use Domain\Event\EventStore;
use Domain\ValueObjects\Chain;
use Domain\ValueObjects\Holders;
use Domain\ValueObjects\Id;
use Domain\ValueObjects\Name;
use Domain\ValueObjects\Price;
use Exception;
use PDO;
use PDOException;

class PDOCryptocurrencyRepository implements CryptocurrencyRepository
{
    private $db;

    private EventStore $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
        try {
            $this->db = new PDO("pgsql:host=192.168.178.36;port=5432;dbname=crypto", 'root', 'alerts', array(
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', //after php5.3.6
            ));
            //$p->exec('SET NAMES utf8');
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";

        }
    }


    public function save(Transaction $transaction): void
    {

        $this->db->beginTransaction();

        $sql = 'INSERT INTO transactions (transaction_id, name,price,chain,holders,occured_on)
                VALUES (:id,:name,:price,:chain,null,NOW())
                ON CONFLICT (transaction_id) DO UPDATE
                SET transaction_id = :id,
                    name = :name,
                    price = :price,
                    chain = :chain,
                    holders = null,
                    occured_on = NOW()
                    WHERE  EXTRACT(MINUTE FROM current_timestamp) - EXTRACT(MINUTE FROM (SELECT occured_on FROM transactions WHERE transaction_id=:id)) > 1200';

        try {
            $stm = $this->db->prepare(
                $sql
            );

            $id = $transaction->id()->asString();
            $name = $transaction->name()->asString();
            $price = $transaction->price()->asFloat();
            $chain = $transaction->chain()->asString();

            $stm->bindParam(":id", $id);
            $stm->bindParam(":name", $name);
            $stm->bindParam(":price", $price);
            $stm->bindParam(":chain", $chain);
            $stm->execute();
            $this->db->commit();

        } catch (Exception $e) {
            $this->db->rollback();
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function addPotentialPumpAndDump(Transaction $transaction)
    {
        $this->db->beginTransaction();

        $sql = 'INSERT INTO transactions (transaction_id, name,price,chain,holders,occured_on)
                VALUES (:id,:name,:price,:chain,null,NOW())
                ON CONFLICT (transaction_id) DO UPDATE
                SET transaction_id = :id,
                    name = :name,
                    price = :price,
                    chain = :chain,
                    holders = null
                    occured_on = NOW()';

        try {
            $stm = $this->db->prepare(
                $sql
            );

            $id = $transaction->id()->asString();
            $name = $transaction->name()->asString();
            $price = $transaction->price()->asFloat();
            $chain = $transaction->chain()->asString();

            $stm->bindParam(":id", $id);
            $stm->bindParam(":name", $name);
            $stm->bindParam(":price", $price);
            $stm->bindParam(":chain", $chain);
            $stm->execute();
            $this->db->commit();

        } catch (Exception $e) {
            $this->db->rollback();
            echo $e->getMessage() . PHP_EOL;
        }
    }


    public function update($id, $price): void
    {
        $sql = 'UPDATE transactions SET price = ? 
                                    AND occured_on = NOW() 
WHERE  cryptocurrency_id = ? AND isBlacklisted = false ';
        $stm = $this->db->prepare($sql);

        $stm->execute();
    }

    public function byComplete(Name $name): mixed
    {
        $sql = 'SELECT isComplete, isBlacklisted FROM transactions WHERE name = ?';
        $stm = $this->db->prepare($sql);
        $stm->execute([$name]);

        return $stm->fetch();
    }


    public function byCompleted(Id $id): mixed
    {
        $events = $this->eventStore->getEventsFor($id->asString());
        if ($events !== null) {
            return Transaction::reconstitute($events);
        }

        $param = $id->asString();

        $sql = 'SELECT isComplete, isBlacklisted FROM transactions WHERE id = :id';
        $stm = $this->db->prepare($sql);
        $stm->bindParam(":id", $param);
        $stm->execute();

        return $this->format($stm->fetch());
    }


    public function byId(Id $id): Transaction
    {

        $events = $this->eventStore->getEventsFor($id->asString());

        if ($events !== null) {
            return Transaction::reconstitute($events);
        }


        $stm = $this->db->prepare(
            'SELECT * FROM transactions WHERE cryptocurrency_id = :id'
        );
        $param = $id->asString();
        $stm->bindParam(':id', $param);

        $stm->execute();

        return $this->format($stm->fetch());
    }

    public function addToBlackList(Id $id): void
    {
        $sql = 'UPDATE transactions SET isBlacklisted = true WHERE  cryptocurrency_id = ? AND isBlacklisted=false';
        $stm = $this->db->prepare($sql);
        $stm->execute([$id]);
    }

    public function updateHolders(Id $id, Holders $holders)
    {
        $sql = 'UPDATE transactions SET holders = :holders, occured_on = NOW(), isComplete = true WHERE  cryptocurrency_id = :id AND isComplete = false AND isBlacklisted=false ';
        $stm = $this->db->prepare($sql);
        $stm->bindParam(':holders', $holders);
        $stm->bindParam(':id', $id);
        $stm->execute();
    }

    public function updateAlert(Id $id): void
    {
        $sql = 'UPDATE transactions SET isAlertSent =true , occured_on = NOW() WHERE  cryptocurrency_id = :id AND isComplete =true AND isBlacklisted=false ';
        $stm = $this->db->prepare($sql);
        $stm->bindParam(':id', $id);
        $stm->execute();
    }


    public function findAllNotComplete(): array
    {

        $this->eventStore->findAllNotComplete();
        $sql = 'SELECT * FROM transactions';
        $stm = $this->db->prepare($sql);
        $stm->execute();
        return $this->createCollectionFrom($stm->fetchAll());
    }

    public function findAllCompletedNotSent(): array
    {
        $sql = 'SELECT * FROM transactions WHERE isComplete = true AND isBlacklisted = false AND isAlertSent = false';
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

    private function format(mixed $result): ?Transaction
    {
        if (empty($result)) {
            return null;
        }

        $transaction_id = null;
        if (isset($result['transaction_id'])) {
            $transaction_id = Id::fromString($result['transaction_id']);
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

        $transaction = Transaction::fromParams(
            $transaction_id,
            $name,
            $price,
            $chain,
        );

        return $transaction;
    }

}
