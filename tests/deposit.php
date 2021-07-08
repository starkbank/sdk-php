<?php

namespace Test\Deposit;
use \Exception;
use StarkBank\Deposit;
use \DateTime;


class TestDeposit
{
    public function queryAndGet()
    {
        $deposits = iterator_to_array(Deposit::query(["limit" => 10, "before" => new DateTime("now")]));

        if (count($deposits) != 10) {
            throw new Exception("failed");
        }

        $deposit = Deposit::get($deposits[0]->id);

        if ($deposits[0]->id != $deposit->id) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Deposit::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $deposit) {
                if (in_array($deposit->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $deposit->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) != 10) {
            throw new Exception("failed");
        }
    }
}

echo "\n\nDeposit:";

$test = new TestDeposit();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
