<?php

namespace App\Infrastructure\Read;

use PDO;
use PDOException;

class TransactionsUsingSql implements Transactions
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

    public function listAllAvailableEbooks(): array
    {
        $sql = 'SELECT * FROM transactions';
        $stm = $this->db->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }

}