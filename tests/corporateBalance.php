<?php

namespace Test\CorporateBalance;
use \Exception;
use StarkBank\CorporateBalance;


class TestCorporateBalance
{
    public function get()
    {
        $balance = CorporateBalance::get();

        if (is_null($balance->id)) {
            throw new Exception("failed");
        }
    }
}

echo "\n\nCorporateBalance:";

$test = new TestCorporateBalance();

echo "\n\t- get";
$test->get();
echo " - OK";
