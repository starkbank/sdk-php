<?php

namespace Test\DepositLog;
use \Exception;
use StarkBank\Deposit\Log;


class TestDepositLog
{
    public function queryAndGet()
    {
        $depositLogs = iterator_to_array(Log::query(["limit" => 10, "types" => ["created"]]));

        if (count($depositLogs) != 10) {
            throw new Exception("failed");
        }

        foreach($depositLogs as $log) {
            if ($log->type != "created") {
                throw new Exception("failed");
            }
        }

        $depositLog = Log::get($depositLogs[0]->id);

        if ($depositLogs[0]->id != $depositLog->id) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Log::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $depositLog) {
                if (in_array($depositLog->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $depositLog->id);
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

echo "\n\nDepositLog:";

$test = new TestDepositLog();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
