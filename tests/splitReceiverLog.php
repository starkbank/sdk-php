<?php

namespace Test\splitReceiverLog;
use \Exception;
use StarkBank\SplitReceiver\Log;


class TestSplitReceiverLog
{
    public function queryAndGet()
    {
        $splitReceiverLogs = iterator_to_array(Log::query(["limit" => 10]));

        if (count($splitReceiverLogs) != 10) {
            throw new Exception("failed");
        }

        $splitReceiverLog = Log::get($splitReceiverLogs[0]->id);

        if ($splitReceiverLogs[0]->id != $splitReceiverLog->id) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Log::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $splitReceiverLog) {
                if (in_array($splitReceiverLog->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $splitReceiverLog->id);
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

echo "\n\nsplitReceiverLog:";

$test = new TestsplitReceiverLog();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
