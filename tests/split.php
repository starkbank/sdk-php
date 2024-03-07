<?php

namespace Test\Split;
use \Exception;
use StarkBank\Split;
use StarkBank\Invoice;
use \DateTime;


class TestSplit
{
    public function createSplitInvoice()
    {
        $invoice = self::examples();
        $invoice = Invoice::create($invoice);
        if (count($invoice[0]->splits[0]) != 2) {
            throw new Exception("failed");
        }
    }
    
    public function queryAndGet()
    {
        $splits = iterator_to_array(Split::query(["limit" => 10, "before" => new DateTime("now")]));
        if (count($splits) != 10) {
            throw new Exception("failed");
        }

        $split = Split::get($splits[0]->id);
        if ($splits[0]->id != $split->id) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Split::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $split) {
                if (in_array($split->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $split->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) != 10) {
            throw new Exception("failed");
        }
    }

    public static function examples()
    {
        return [
            
            new Invoice([
                "amount" => 400000,
                "taxId" => "012.345.678-90",
                "name" => "Mr Meeseks",
                "splits" => [
                    new Split([
                        "receiverId" => "5706627130851328",
                        "amount" => 200000,
                    ]),
                    new Split([
                        "receiverId" => "5743243941642240",
                        "amount" => 200000,
                    ]),
                ]
            ])
        ];
    }
}

echo "\n\nSplit:";

$test = new TestSplit();

echo "\n\t- create split invoice\n";
$test->createSplitInvoice();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";