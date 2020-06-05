<?php

namespace Test\Transfer;
use \Exception;
use StarkBank\Transfer;


class Test
{
    public function create()
    {
        $transfer = Transfer::create([Test::example()])[0];

        if (is_null($transfer->id)) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $transfers = iterator_to_array(Transfer::query(["limit" => 10]));

        if (count($transfers) != 10) {
            throw new Exception("failed");
        }

        $transfer = Transfer::get($transfers[0]->id);

        if ($transfers[0]->id != $transfer->id) {
            throw new Exception("failed");
        }
    }

    public function queryAndGetPdf()
    {
        $transfers = iterator_to_array(Transfer::query(["limit" => 10, "status" => "success"]));

        if (count($transfers) != 10) {
            throw new Exception("failed");
        }

        $pdf = Transfer::pdf($transfers[0]->id);

        $fp = fopen('transfer.pdf', 'w');
        fwrite($fp, $pdf);
        fclose($fp);
    }

    private static function example()
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
