<?php

namespace Test\Transfer;
use DateInterval;
use DateTime;
use \Exception;
use StarkBank\Transfer;


class TestTransfer
{
    public function create()
    {
        $transfer = Transfer::create([TestTransfer::example()])[0];

        if (is_null($transfer->id)) {
            throw new Exception("failed");
        }
    }

    public function createAndDelete()
    {
        $transfer = Transfer::create([TestTransfer::example(true)])[0];
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

    public function queryIds()
    {
        $transfers = iterator_to_array(Transfer::query(["limit" => 10]));
        $transfersIdsExpected = array();
        for ($i=0; $i<sizeof($transfers); $i++){
            array_push($transfersIdsExpected, $transfers[$i]->id);
        }

        $transfersResult = iterator_to_array(Transfer::query((["ids" => $transfersIdsExpected])));
        $transfersIdsResult = array();
        for ($i=0; $i<sizeof($transfersResult); $i++){
            array_push($transfersIdsResult, $transfersResult[$i]->id);
        }

        sort($transfersIdsExpected);
        sort($transfersIdsResult);

        if ($transfersIdsExpected != $transfersIdsResult) {
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

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Transfer::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $transfer) {
                if (in_array($transfer->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $transfer->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) != 10) {
            throw new Exception("failed");
        }
    }

    public static function example($schedule=false)
    {
        $params = [
            "amount" => 10,
            "name" => "João da Silva",
            "description" => "Test description",
            "taxId" => "012.345.678-90",
            "bankCode" => "01",
            "branchCode" => "0001",
            "accountNumber" => "10000-0",
            "accountType" => "checking",
            "externalId" => "php-" . $uuid = mt_rand(0, 0xffffffff)
        ];
        if ($schedule) {
            $datetime = (new DateTime("now"))->add(new DateInterval("P1D"));
            $date = date('Y-m-d', strtotime(date("Y-m-d"). ' + 3 days'));
            $dateTypes = array($datetime, $date);
            $params["scheduled"] = $dateTypes[array_rand($dateTypes)];
        };
        return new Transfer($params);
    }
}

echo "\n\nTransfer:";

$test = new TestTransfer();

echo "\n\t- create";
$test->create();
echo " - OK";

echo "\n\t- create and delete";
$test->createAndDelete();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- query";
$test->queryIds();
echo " - OK";

echo "\n\t- query and get PDF";
$test->queryAndGetPdf();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
