<?php

namespace Test\Transfer;
use \Exception;
use Test\TestUser;
use StarkBank\Transfer;


class Test
{
    public function create()
    {
        $user = TestUser::project();

        $transfer = Transfer::create($user, [Test::example()])[0];

        if (is_null($transfer->id)) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $user = TestUser::project();

        $transfers = iterator_to_array(Transfer::query($user, ["limit" => 10]));

        if (count($transfers) != 10) {
            throw new Exception("failed");
        }

        $transfer = Transfer::get($user, $transfers[0]->id);

        if ($transfers[0]->id != $transfer->id) {
            throw new Exception("failed");
        }
    }

    public function queryAndGetPdf()
    {
        $user = TestUser::project();

        $transfers = iterator_to_array(Transfer::query($user, ["limit" => 10, "status" => "success"]));

        if (count($transfers) != 10) {
            throw new Exception("failed");
        }

        $pdf = Transfer::pdf($user, $transfers[0]->id);

        $fp = fopen('transfer.pdf', 'w');
        fwrite($fp, $pdf);
        fclose($fp);
    }

    private function example()
    {
        return new Transfer([
            "amount" => 10,
            "name" => "João da Silva",
            "taxId" => "012.345.678-90",
            "bankCode" => "01",
            "branchCode" => "0001",
            "accountNumber" => "10000-0",
        ]);
    }
}

echo "\n\nTransfer:";

$test = new Test();

echo "\n\t- create";
$test->create();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- query and get PDF";
$test->queryAndGetPdf();
echo " - OK";
