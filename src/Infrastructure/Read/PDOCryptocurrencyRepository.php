<?php

namespace App\Infrastructure\Read;



use App\ValueObjects\Id;

class PDOCryptocurrencyRepository
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

        }
    }


    public function save(DTOTransaction $transaction): void
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



    public function byId(Id $id): Id
    {


        $stm = $this->db->prepare(
            'SELECT * FROM transactions WHERE cryptocurrency_id = :id'
        );
        $param = $id->asString();
        $stm->bindParam(':id', $param);

        $stm->execute();

        return $this->format($stm->fetch());
    }



    public function createCollectionFrom(array $notCompleted): array
    {
        $returnArr = [];
        foreach ($notCompleted as $value) {
            $returnArr[] = $this->format($value);
        }

        return $returnArr;
    }

    private function format(mixed $result): ?DTOTransaction
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

        $transaction = DTOTransaction::fromParams(
            $transaction_id,
            $name,
            $price,
            $chain,
        );

        return $transaction;
    }

}
