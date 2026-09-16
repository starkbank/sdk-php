<?php

namespace Test\VerifiedAccountLog;
use \Exception;
use StarkBank\VerifiedAccount\Log;


class TestVerifiedAccountLog
{
    public function queryAndGet()
    {
        $accountLogs = iterator_to_array(Log::query(["limit" => 2, "types" => ["created"]]));

        if (count($accountLogs) != 2) {
            throw new Exception("failed");
        }

        foreach($accountLogs as $log) {
            if ($log->type != "created") {
                throw new Exception("failed");
            }
        }

        $accountLog = Log::get($accountLogs[0]->id);

        if ($accountLogs[0]->id != $accountLog->id) {
            throw new Exception("failed");
        }
        if (is_null($accountLog->account->id)) {
            throw new Exception("failed");
        }
    }

    public function queryIds()
    {
        $accountLogs = iterator_to_array(Log::query(["limit" => 10]));
        $accountIds = array();
        foreach ($accountLogs as $log) {
            if (!in_array($log->account->id, $accountIds)) {
                array_push($accountIds, $log->account->id);
            }
        }

        $filteredLogs = iterator_to_array(Log::query(["accountIds" => $accountIds]));

        if (count($filteredLogs) == 0) {
            throw new Exception("failed");
        }

        foreach ($filteredLogs as $log) {
            if (!in_array($log->account->id, $accountIds)) {
                throw new Exception("failed");
            }
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) {
            list($page, $cursor) = Log::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $accountLog) {
                if (in_array($accountLog->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $accountLog->id);
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

echo "\n\nVerifiedAccountLog:";

$test = new TestVerifiedAccountLog();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- query ids";
$test->queryIds();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
