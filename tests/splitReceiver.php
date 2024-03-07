<?php

namespace Test\SplitReceiver;
use \Exception;
use StarkBank\SplitReceiver;
use \DateTime;


class TestSplitReceiver
{
    public function createSplitReceiver()
    {
        $receivers = self::examples();
        $receivers = SplitReceiver::create($receivers);

        if (is_null($receivers)) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $splitReceivers = iterator_to_array(SplitReceiver::query(["limit" => 10, "before" => new DateTime("now")]));

        if (count($splitReceivers) != 10) {
            throw new Exception("failed");
        }

        $splitReceiver = SplitReceiver::get($splitReceivers[0]->id);
        if ($splitReceivers[0]->id != $splitReceiver->id) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = SplitReceiver::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $splitReceiver) {
                if (in_array($splitReceiver->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $splitReceiver->id);
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
            new SplitReceiver([
                "name"=> "João",
                "taxId"=> "01234567890",
                "bankCode"=> "18236120",
                "branchCode"=> "0001",
                "accountNumber"=> "10000-0",
                "accountType"=> "checking",
                "tags"=> ["test sdk-php"],
            ]),
            new SplitReceiver([
                "name"=> "Maria",
                "taxId"=> "01234567890",
                "bankCode"=> "18236120",
                "branchCode"=> "0001",
                "accountNumber"=> "10000-0",
                "accountType"=> "checking",
                "tags"=> ["test sdk-php"],
            ]),
        ];
    }
}

echo "\n\nSplitReceiver:";

$test = new TestSplitReceiver();

echo "\n\t- create split receiver\n";
$test->createSplitReceiver();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";