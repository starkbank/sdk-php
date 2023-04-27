<?php

namespace Test\CorporateWithdrawal;
use \Exception;
use StarkBank\CorporateWithdrawal;


class TestCorporateWithdrawal
{

    public function create()
    {
        $withdrawal = CorporateWithdrawal::create(TestCorporateWithdrawal::example());

        if (is_null($withdrawal->id)) {
            throw new Exception("failed");
        }
    }

    public function query()
    {
        $withdrawals = CorporateWithdrawal::query(["limit" => 10]);

        foreach ($withdrawals as $withdrawal) {
            if (is_null($withdrawal->id)) {
                throw new Exception("failed");
            }
        }
    }

    public function get()
    {
        $withdrawal = iterator_to_array(CorporateWithdrawal::query(["limit" => 1]))[0];
        $withdrawal = CorporateWithdrawal::get($withdrawal->id);
        if (!is_string($withdrawal->id)) {
            throw new Exception("failed");
        }
    }

    public function page()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = CorporateWithdrawal::page(["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $withdrawal) {
                print_r($withdrawal);
                if (is_null($withdrawal->id) or in_array($withdrawal->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $withdrawal->id);
            }
            if ($cursor == null) {
                break;
            }
        }
    }

    public static function example()
    {
        $params = [
            "amount" => 10000,
            "externalId" => strval(rand(0, 99999999999))
        ];
        return new CorporateWithdrawal($params);
    }
}

echo "\n\nCorporateWithdrawal:";

$test = new TestCorporateWithdrawal();

echo "\n\t- create";
$test->create();
echo " - OK";

echo "\n\t- query";
$test->query();
echo " - OK";

echo "\n\t- get";
$test->get();
echo " - OK";

echo "\n\t- page";
$test->page();
echo " - OK";
