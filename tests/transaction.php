<?php

namespace Test\Transaction;
use \Exception;
use Test\TestUser;
use StarkBank\Transaction;


class Test
{
    public function createAndQuery()
    {
        $externalId = uniqid();
        $transactions = [new Transaction(
            1,
            "transaction to random workspace",
            $externalId,
            "5768064935133184"
        )];
        
        $transactions = Transaction::create(TestUser::project(), $transactions);

        if ($transactions[0]->externalId != $externalId) {
            throw new Exception("failed");
        }

        $transactions = iterator_to_array(Transaction::query(TestUser::project(), 10, ["externalIds" => [$externalId]]));

        if (count($transactions) != 1) {
            throw new Exception("failed");
        }
        
        if ($transactions[0]->externalId != $externalId) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $transactions = iterator_to_array(Transaction::query(TestUser::project(), 101));
        if (count($transactions) > 101) {
            throw new Exception("failed");
        }
        if (!is_int($transactions[0]->amount)) {
            throw new Exception("failed");
        }
        $transaction = Transaction::get(TestUser::project(), $transactions[0]->id);
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
