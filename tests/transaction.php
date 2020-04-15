<?php

namespace Test\Transaction;
use \Exception;
use StarkBank\Transaction;


class Test
{
    public function createAndQuery()
    {
        $externalId = uniqid();
        $transactions = [new Transaction([
            "amount" => 1,
            "description" => "transaction to random workspace",
            "externalId" => $externalId,
            "receiverId" => "5768064935133184",
        ])];

        $transactions = Transaction::create($transactions);

        if ($transactions[0]->externalId != $externalId) {
            throw new Exception("failed");
        }

        $transactions = iterator_to_array(Transaction::query(["limit" => 10, "externalIds" => [$externalId]]));

        if (count($transactions) != 1) {
            throw new Exception("failed");
        }
        
        if ($transactions[0]->externalId != $externalId) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $transactions = iterator_to_array(Transaction::query(["limit" => 101]));
        if (count($transactions) != 101) {
            throw new Exception("failed");
        }
        if (!is_int($transactions[0]->amount)) {
            throw new Exception("failed");
        }
        $transaction = Transaction::get($transactions[0]->id);
        if (!is_int($transaction->amount)) {
            throw new Exception("failed");
        }
    }
}

echo "\n\nTransaction:";

$test = new Test();

echo "\n\t- create and query";
$test->createAndQuery();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";
