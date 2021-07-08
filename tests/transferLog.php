<?php

namespace Test\TransferLog;
use \Exception;
use StarkBank\Transfer\Log;


class TestTransferLog
{
    public function queryAndGet()
    {
        $transferLogs = iterator_to_array(Log::query(["limit" => 10, "types" => ["created"]]));

        if (count($transferLogs) != 10) {
            throw new Exception("failed");
        }

        foreach($transferLogs as $log) {
            if ($log->type != "created") {
                throw new Exception("failed");
            }
        }

        $transferLog = Log::get($transferLogs[0]->id);

        if ($transferLogs[0]->id != $transferLog->id) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Log::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $transferLog) {
                if (in_array($transferLog->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $transferLog->id);
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

echo "\n\nTransferLog:";

$test = new TestTransferLog();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
