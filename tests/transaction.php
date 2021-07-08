<?php

namespace Test\Transaction;
use \Exception;
use StarkBank\Transaction;


class TestTransaction
{
    public function createAndQuery()
    {
        $externalId = uniqid();
        $transactions = [self::example($externalId)];

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

    public function queryIds()
    {

        $transactions = iterator_to_array(Transaction::query(["limit" => 10]));
        $transactionsIdsExpected = array();
        for ($i=0; $i<sizeof($transactions); $i++){
            array_push($transactionsIdsExpected, $transactions[$i]->id);
        }

        $transactionsResult = iterator_to_array(Transaction::query((["ids" => $transactionsIdsExpected])));
        $transactionsIdsResult = array();
        for ($i=0; $i<sizeof($transactionsResult); $i++){
            array_push($transactionsIdsResult, $transactionsResult[$i]->id);
        }

        sort($transactionsIdsExpected);
        sort($transactionsIdsResult);

        if ($transactionsIdsExpected != $transactionsIdsResult) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Transaction::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $transaction) {
                if (in_array($transaction->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $transaction->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) != 10) {
            throw new Exception("failed");
        }
    }

    public static function example($externalId)
    {
        return new Transaction([
            "amount" => 1,
            "description" => "transaction to random workspace",
            "externalId" => $externalId,
            "receiverId" => "5768064935133184",
        ]);
    }

}

echo "\n\nTransaction:";

$test = new TestTransaction();

echo "\n\t- create and query";
$test->createAndQuery();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- query";
$test->queryIds();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
