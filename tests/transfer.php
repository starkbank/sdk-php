<?php

namespace Test\Transfer;
use DateInterval;
use DateTime;
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

    public function createAndDelete()
    {
        $transfer = Transfer::create([Test::example(true)])[0];
        $deletedTransfer = Transfer::delete($transfer->id);
        if ($transfer->id != $deletedTransfer->id) {
            throw new Exception("failed");
        }
        if ($deletedTransfer->status != "canceled") {
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

    private static function example($schedule=false)
    {
        $params = [
            "amount" => 10,
            "name" => "João da Silva",
            "taxId" => "012.345.678-90",
            "bankCode" => "01",
            "branchCode" => "0001",
            "accountNumber" => "10000-0"
        ];
        if ($schedule) {
            $params["scheduled"] = (new DateTime("now"))->add(new DateInterval("P1D"));
        };
        return new Transfer($params);
    }
}

echo "\n\nTransfer:";

$test = new Test();

echo "\n\t- create";
$test->create();
echo " - OK";

echo "\n\t- create and delete";
$test->createAndDelete();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- query and get PDF";
$test->queryAndGetPdf();
echo " - OK";
