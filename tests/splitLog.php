<?php

namespace Test\SplitLog;
use \Exception;
use StarkBank\Split\Log;


class TestSplitLog
{
    public function queryAndGet()
    {
        $splitLogs = iterator_to_array(Log::query(["limit" => 10]));

        if (count($splitLogs) != 10) {
            throw new Exception("failed");
        }

        $splitLog = Log::get($splitLogs[0]->id);

        if ($splitLogs[0]->id != $splitLog->id) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Log::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $splitLog) {
                if (in_array($splitLog->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $splitLog->id);
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

echo "\n\nsplitLog:";

$test = new TestsplitLog();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
