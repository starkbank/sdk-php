<?php

namespace Test\CorporateTransaction;
use \Exception;
use StarkBank\CorporateTransaction;


class TestCorporateTransaction
{
    public function query()
    {
        $transactions = CorporateTransaction::query(["limit" => 10]);

        foreach ($transactions as $transaction) {
            if (is_null($transaction->id)) {
                throw new Exception("failed");
            }
        }
    }

    public function page()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = CorporateTransaction::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $transaction) {
                if (is_null($transaction->id) or in_array($transaction->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $transaction->id);
            }
            if ($cursor == null) {
                break;
            }
        }
    }

    public function get()
    {
        $transaction = iterator_to_array(CorporateTransaction::query(["limit" => 1]))[0];
        $transaction = CorporateTransaction::get($transaction->id);
        if (!is_string($transaction->id)) {
            throw new Exception("failed");
        }
    }
}

echo "\n\nCorporateTransaction:";

$test = new TestCorporateTransaction();

echo "\n\t- query";
$test->query();
echo " - OK";

echo "\n\t- page";
$test->page();
echo " - OK";

echo "\n\t- get";
$test->get();
echo " - OK";
