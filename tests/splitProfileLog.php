<?php

namespace Test\SplitProfileLog;
use \Exception;
use StarkBank\SplitProfile\Log;


class TestSplitProfileLog
{
    public function queryAndGet()
    {
        $splitProfileLogs = iterator_to_array(Log::query(["limit" => 10]));

        if (count($splitProfileLogs) != 10) {
            throw new Exception("failed");
        }

        $splitProfileLog = Log::get($splitProfileLogs[0]->id);

        if ($splitProfileLogs[0]->id != $splitProfileLog->id) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) {
            list($page, $cursor) = Log::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $splitProfileLog) {
                if (in_array($splitProfileLog->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $splitProfileLog->id);
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

echo "\n\nsplitProfileLog:";

$test = new TestsplitProfileLog();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
